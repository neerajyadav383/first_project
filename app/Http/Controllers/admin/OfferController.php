<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function offer($id = '')
    {
        $offers = Offer::all();
        if ($id != '') {
            $editNotify = Offer::where('id', $id)->first();
            // echo '<pre>';
            // print_r($editNotify->notification);
            // echo '</pre>';
            // die();
            return view('admin.offer', ['notifications' => $offers, 'editNotify' => $editNotify]);
        }
        return view('admin.offer', ['notifications' => $offers]);
        // echo '<pre>';
        // print_r($editNotify);
        // echo '</pre>';
        // die();
    }

    public function add_offer(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();

        $user = Auth::user();

        $notification = new Offer;
        $photo  = uploadFile($request->image, $user->userid);
        $notification->title = $request->title;
        $notification->image = $photo;
        $notification->created_at = date('Y-m-d H:i:s');
        $notification->updated_at = date('Y-m-d H:i:s');
        $notification->save();

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Added successfully.");

        return redirect('offer');
    }

    public function delete_offer($id)
    {
        // echo '<pre>';
        // print_r($delete);
        // echo '</pre>';
        // die();
        Offer::where('id', $id)->delete();

        session()->flash('alert-class', 'text-danger');
        session()->flash('message', "Deleted successfully.");

        return redirect('offer');
    }

    public function update_offer(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = Auth::user();
        if ($request->file('image') != null) {
            $photo  = uploadFile($request->image, $user->userid);
            $data = array(
                'title'         => $request->title,
                'image'         => $photo,
                'status'        => $request->status,
                'updated_at'    => date('Y-m-d H:i:s'),
            );
        } else {
            $data = array(
                'title'         => $request->title,
                'status'        => $request->status,
                'updated_at'    => date('Y-m-d H:i:s'),
            );
        }
        Offer::where('id', $request->id)->update($data);

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Updated successfully.");

        return redirect('offer');
    }
}
