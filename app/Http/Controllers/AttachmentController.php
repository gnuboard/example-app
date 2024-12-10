<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AttachmentController extends Controller
{
    public function download($id)
    {
        try {
            $attachment = Attachment::findOrFail($id);
            
            // 파일 존재 여부 확인
            if (!Storage::disk('public')->exists($attachment->file_path)) {
                Log::error("File not found in storage: {$attachment->file_path}");
                return back()->with('error', '파일을 찾을 수 없습니다.');
            }

            return Storage::disk('public')->download(
                $attachment->file_path,
                $attachment->original_filename,
                [
                    'Content-Type' => $attachment->mime_type,
                    'Content-Disposition' => 'attachment; filename="' . rawurlencode($attachment->original_filename) . '"'
                ]
            );

        } catch (\Exception $e) {
            Log::error('File download error: ' . $e->getMessage());
            return back()->with('error', '파일 다운로드 중 오류가 발생했습니다.');
        }
    }
} 