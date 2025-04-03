<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\SupportFile;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class SupportTicketController extends Controller
{
    public function index() {

        Session::put("menu_active", true);
        $title = translate("Support Ticket");
        $tickets = SupportTicket::where('user_id', auth()->user()->id)
                                    ->search(['name', 'email', 'subject'])
                                    ->latest()
                                    ->with('user')
                                    ->routefilter()
                                    ->paginate(paginateNumber(site_settings("paginate_number")))
                                    ->appends(request()->all());
        return view('user.support.index', compact('title', 'tickets'));
    }

    public function create()
    {
        Session::put("menu_active", true);
        $title = translate("Create new ticket");
        return view('user.support.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:255',
            'priority' => 'required|in:1,2,3',
            'message' => 'required',
        ]);
        $user = auth()->user();
        $supportTicket = new SupportTicket();
        $supportTicket->ticket_number = randomNumber();
        $supportTicket->user_id = $user->id;
        $supportTicket->name = @$user->user;
        $supportTicket->subject = $request->subject;
        $supportTicket->priority = $request->priority;
        $supportTicket->status = 1;
        $supportTicket->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $supportTicket->id;
        $message->admin_id = null;
        $message->message = $request->message;
        $message->save();

        if($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                try {
                    $supportFile = new SupportFile();
                    $supportFile->support_message_id = $message->id;
                    $supportFile->file = uploadNewFile($file, filePath()['ticket']['path']);
                    $supportFile->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your ' . $file];
                    return back()->withNotify($notify);
                }
            }
        }
        $notify[] = ['success', "Support ticket has been created"];
        return redirect()->back()->withNotify($notify);
    }

    public function ticketDetails($id)
    {
        $title = translate("Ticket Reply");
        $user = auth()->user();
        $ticket = SupportTicket::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        return view('user.support.detail', compact('title', 'ticket'));
    }

    public function ticketReply(Request $request, $id)
    {
        $user = auth()->user();
        $supportTicket = SupportTicket::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $supportTicket->status = 3;
        $supportTicket->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $supportTicket->id;
        $message->admin_id = null;
        $message->message = $request->message;
        $message->save();
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                try {
                    $supportFile = new SupportFile();
                    $supportFile->support_message_id = $message->id;
                    $supportFile->file = uploadNewFile($file, filePath()['ticket']['path']);
                    $supportFile->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your ' . $file];
                    return back()->withNotify($notify);
                }
            }
        }
        $notify[] = ['success', "Support ticket replied successfully"];
        return back()->withNotify($notify);
    }

    public function closedTicket($id)
    {
        $user = auth()->user();
        $supportTicket =  SupportTicket::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $supportTicket->status = 4;
        $supportTicket->save();
        $notify[] = ['success', "Support ticket has been closed"];
        return back()->withNotify($notify);
    }

    public function supportTicketDownloader($id)
    {
        $supportFile = SupportFile::findOrFail(decrypt($id));
        $file = $supportFile->file;
        $path = filePath()['ticket']['path'].'/'.$file;
        $title = slug('file').'-'.$file;
        $mimetype = mime_content_type($path);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($path);
    }
}
