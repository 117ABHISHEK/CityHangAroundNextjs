<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpamWord;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class SpamWordController extends Controller
{
    public function index()
    {
        $spamWords = SpamWord::first(); // Get the single row where words are stored as a CSV string
        $page_data['spamWords'] = $spamWords ? explode(',', $spamWords->word) : []; 
        $page_data['view_path'] = 'spam.index';
        return view('backend.index', $page_data);
    }

    public function store(Request $request)
    {
        $request->validate(['word' => 'required|string']);

        $wordsArray = array_map('trim', explode(',', strtolower($request->word))); // Convert input into an array
        $existingSpamWord = SpamWord::first();

        if ($existingSpamWord) {
            $updatedWords = array_unique(array_merge(explode(',', $existingSpamWord->word), $wordsArray));
            $existingSpamWord->word = implode(',', $updatedWords);
            $existingSpamWord->save();
        } else {
            SpamWord::create(['word' => implode(',', $wordsArray)]);
        }

        return back()->with('success', 'Spam words added!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
        ]);
    
        // Get the first (only) row from the database
        $spamWord = SpamWord::first();
    
        if ($spamWord) {
            $spamWord->word = $request->word;
            $spamWord->save();
        } else {
            SpamWord::create(['word' => $request->word]);
        }
    
        return response()->json(['success' => true, 'message' => 'Spam words updated successfully']);
    }
    

    public function destroy($id)
    {
        $spamWord = SpamWord::findOrFail($id);
        $spamWord->delete();
        return back()->with('success', 'Spam words deleted!');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = $request->file('file');
        $content = file_get_contents($file);
        $wordsArray = array_map('trim', explode(',', strtolower($content)));

        $existingSpamWord = SpamWord::first();

        if ($existingSpamWord) {
            $updatedWords = array_unique(array_merge(explode(',', $existingSpamWord->word), $wordsArray));
            $existingSpamWord->word = implode(',', $updatedWords);
            $existingSpamWord->save();
        } else {
            SpamWord::create(['word' => implode(',', $wordsArray)]);
        }

        return back()->with('success', 'Spam words imported successfully!');
    }

    public function downloadTemplate()
    {
        $csv = Writer::createFromString('');
        $csv->insertOne(['Spam Words']); // Header
        $csv->insertOne(['word1, word2, word3']); // Example row

        $filename = 'spam_words_template.csv';
        Storage::put("public/$filename", $csv->toString());

        return response()->download(storage_path("app/public/$filename"))->deleteFileAfterSend(true);
    }
}
