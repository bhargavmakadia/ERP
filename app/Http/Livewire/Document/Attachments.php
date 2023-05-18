<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\Document;
use Livewire\WithFileUploads;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class Attachments extends Component
{
    public $document, $file, $iteration=1, $progress=0;
    use WithFileUploads;
    
    public function updatedFile()
    {
        $this->validate([
            'file' => 'mimes:png,jpg,pdf,jpeg,cdr,ai,eps,tiff,docx,doc|max:102400', // 100MB Max
        ]);
        $filePath = $this->file->store('attachments','public');

        $data['name']=$this->file->getClientOriginalName();
        $data['file_path']=$filePath;
        $data['user_id']=\Auth::id();
        $this->document->attachments()->create($data);
        $this->document->refresh();
        $this->reset(['file']);
        $this->iteration++;
    }

    public function delete(Attachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
        $this->document->refresh();
    }

    public function render()
    {
        return view('livewire.document.attachments');
    }
}
