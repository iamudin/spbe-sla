<?php

namespace App\Models\Plugins\SpbeSla;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TicketComment extends Model
{
    use \Leazycms\FLC\Traits\Fileable;
    
    protected $table = 'spbe_sla_ticket_comments';
    
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
