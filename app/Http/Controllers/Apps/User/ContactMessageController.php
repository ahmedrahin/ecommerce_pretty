<?php

namespace App\Http\Controllers\Apps\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\DataTables\ContactMessageDataTable;
use App\DataTables\WeeklyMessageDataTable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ContactMessageController extends Controller
{
    public function index(ContactMessageDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.message.list');
    }

    public function show(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('pages.apps.message.details', compact('message'));
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json(['success' => true]);
    }

    public function weekly(WeeklyMessageDataTable $dataTable)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = ContactMessage::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        return $dataTable->render('pages.apps.message.weekly', compact('data'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $to = $request->email;
        $contactMessage = ContactMessage::findOrFail($id);

        // Save reply info to database
        $contactMessage->update([
            'is_replied' => true,
            'reply_message' => $request->reply_message,
            'replied_at' => now(),
            'user_id' => auth()->id(),
        ]);

        $subjectContent = $request->subject ?? 'Reply your message from ' . config('app.name') ;
        $body = $request->reply_message ?? '';

        // Send email
        Mail::send('email.contact-message', [
            'body' => $body,
            'subjectContent' => $subjectContent,
            'messageData' => $contactMessage,
        ], function ($mail) use ($to, $subjectContent, $request) {
            $mail->to($to)
                ->subject($subjectContent);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $mail->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType(),
                    ]);
                }
            }
        });

        return back()->with('success', 'Email sent successfully.');
    }


}
