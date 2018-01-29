<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Category extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'parent_id'     => $this->parent_id,
            'name'          => $this->name,
            'path'          => $this->path,
            'description'   => $this->description,
            'image_url'     => $this->image_url,
            'created_at'    => $this->created_at->toDateTimeString()
        ];
    }
}
