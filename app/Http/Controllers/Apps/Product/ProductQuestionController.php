<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\DataTables\ProductQuestionDataTable;
use App\DataTables\WeeklyProductQuestionDataTable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ProductQuestionController extends Controller
{
    public function index(ProductQuestionDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.question.list');
    }

    public function show(string $id)
    {
        $message = Question::findOrFail($id);
        return view('pages.apps.question.details', compact('message'));
    }

     public function destroy($id)
    {
        $message = Question::findOrFail($id);
        $message->delete();

        return response()->json(['success' => true]);
    }

     public function weekly(WeeklyProductQuestionDataTable $dataTable)
        {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $data = Question::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
            return $dataTable->render('pages.apps.question.weekly', compact('data'));
        }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $to = $request->email;
        $contactMessage = Question::findOrFail($id);

        // Save reply info to database
        $contactMessage->update([
            'is_replied' => true,
            'answer' => $request->reply_message,
            'replied_at' => now(),
            'replied_user_id' => auth()->id(),
        ]);

        $subjectContent = $request->subject ?? 'Answer to your question from ' . config('app.name') ;
        $body = $request->reply_message ?? '';

        // Send email
        Mail::send('email.answer', [
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
