<?php
namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasUuids, SoftDeletes, \Leazycms\FLC\Traits\Fileable;

    protected $table = 'spbe_sla_tickets';

    protected $fillable = [
        'ticket_number',
        'digital_service_id',
        'user_id',
        'assigned_agent_id',
        'category',
        'priority',
        'status',
        'title',
        'description',
        'response_due_at',
        'resolution_due_at',
        'responded_at',
        'resolved_at',
    ];

    protected $casts = [
        'response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            // Auto-generate ticket number if empty
            if (empty($ticket->ticket_number)) {
                $year = date('Y');
                $lastTicket = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
                $seq = $lastTicket ? (intval(substr($lastTicket->ticket_number, -3)) + 1) : 1;
                $ticket->ticket_number = 'TCK-' . $year . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
            }

            // Calculate SLA deadlines if Digital Service is set
            if ($ticket->digital_service_id && empty($ticket->response_due_at)) {
                $activeSla = SlaPolicy::where('digital_service_id', $ticket->digital_service_id)
                                      ->where('effective_date', '<=', Carbon::now())
                                      ->orderBy('effective_date', 'desc')
                                      ->first();
                
                if ($activeSla) {
                    $multiplier = 1;
                    // Apply priority multipliers
                    switch ($ticket->priority) {
                        case 'Urgent/P1': $multiplier = 0.25; break;
                        case 'High': $multiplier = 0.5; break;
                        case 'Medium': $multiplier = 1; break;
                        case 'Low': $multiplier = 2; break;
                    }

                    $responseMinutes = $activeSla->response_time_target_minutes * $multiplier;
                    $resolutionHours = $activeSla->resolution_time_target_hours * $multiplier;

                    $ticket->response_due_at = Carbon::now()->addMinutes($responseMinutes);
                    $ticket->resolution_due_at = Carbon::now()->addHours($resolutionHours);
                }
            }
        });
        
        static::updating(function ($ticket) {
            // Automatically set responded_at when status changes from Open to Assigned/In Progress
            if ($ticket->isDirty('status') && in_array($ticket->status, ['Assigned', 'In Progress']) && empty($ticket->responded_at)) {
                $ticket->responded_at = Carbon::now();
            }
            
            // Automatically set resolved_at when status changes to Resolved/Closed
            if ($ticket->isDirty('status') && in_array($ticket->status, ['Resolved', 'Closed']) && empty($ticket->resolved_at)) {
                $ticket->resolved_at = Carbon::now();
            }
        });
    }

    public function digitalService()
    {
        return $this->belongsTo(DigitalService::class, 'digital_service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function appDevelopment()
    {
        return $this->hasOne(AppDevelopment::class, 'ticket_id');
    }
}
