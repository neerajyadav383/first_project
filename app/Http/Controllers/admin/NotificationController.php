<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function notification($id = '')
    {
        $notifications = Notification::all();
        if ($id != '') {
            $editNotify = Notification::where('id', $id)->first();
            // echo '<pre>';
            // print_r($editNotify->notification);
            // echo '</pre>';
            // die();
            return view('admin.notification', ['notifications' => $notifications, 'editNotify' => $editNotify]);
        }
        return view('admin.notification', ['notifications' => $notifications]);
        // echo '<pre>';
        // print_r($editNotify);
        // echo '</pre>';
        // die();
    }

    public function addNotification(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $notification = new Notification;

        $notification->notification = $request->notification;
        $notification->created_at = date('Y-m-d H:i:s');
        $notification->updated_at = date('Y-m-d H:i:s');
        $notification->save();

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Added successfully.");

        return redirect('notification');
    }

    public function deleteNotification($id)
    {
        // echo '<pre>';
        // print_r($delete);
        // echo '</pre>';
        // die();
        Notification::where('id', $id)->delete();

        session()->flash('alert-class', 'text-danger');
        session()->flash('message', "Deleted successfully.");

        return redirect('notification');
    }

    public function updateNotification(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();

        $data = array(
            'notification' => $request->notification,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        Notification::where('id', $request->id)->update($data);

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Updated successfully.");

        return redirect('notification');
    }
}
