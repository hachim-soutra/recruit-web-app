<?php

namespace App\Services\Admin;

use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ResourceFileService
{
    public function paginate($queries): array
    {
        $search = false;
        $resources = Resource::whereNotNull('filename');
        if ($queries !== null) {
            $search = true;
            $resources->where("filename", "like", "%" . $queries . "%")
                ->orWhere("mimetype", "like", "%" . $queries . "%");
        }
        $count = $resources->count();
        $resources = $resources->orderByRaw('id desc')->paginate(8);

        return array(
            'count'              => $count,
            'resources'         => $resources,
            'search'             => $search
        );
    }

    public static function store($data): bool
    {
        $resource = new Resource();
        $resource->filename = $data['filename'];
        if (isset($data['file'])) {
            $file      = $data['file'];
            $extension = $file->getClientOriginalExtension();
            $mimetype  = $file->getMimeType();
            $filesize  = $file->getSize();
            $filename  = $data['filename'] .'.'. $extension;
            $file->move(public_path('uploads/resource_files/'), $filename);

            $resource->mimetype = $mimetype;
            $resource->filepath = 'resource_files/'.$filename;
            $resource->filesize = $filesize;

            return $resource->save();
        }
        return false;
    }

    public function delete($id)
    {
        $resource = Resource::find($id);
        $result = $resource->deleteOrFail();
        if ($result) {
            $segments = explode('/', $resource->filepath);
            @unlink(public_path('uploads/resource_files/').'/'.end($segments));
            return $result;
        } else {
            return false;
        }

    }

}
