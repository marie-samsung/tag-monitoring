<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\NotificationsModel;
use App\Models\Constants;

class NotificationsController extends BaseController
{
    public function getUnread()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not logged in.']);
        }

        $model = new NotificationsModel();
        $notifications = $model->getUserNotifications($userId, 20);
        
        $unreadCount = 0;
        // Enrich notifications with sender name
        foreach ($notifications as &$notif) {
            $notif['sender_name'] = Constants::USERS[$notif['sender_id']] ?? 'System';
            $notif['time_ago'] = $this->timeElapsedString($notif['created_at']);
            if ($notif['is_read'] == 0) {
                $unreadCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function markAsRead($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false]);
        }

        $model = new NotificationsModel();
        $model->update($id, ['is_read' => 1]);

        return $this->response->setJSON(['success' => true, 'csrf_hash' => csrf_hash()]);
    }

    private function timeElapsedString($datetime, $full = false) 
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
