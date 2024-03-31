<ul>
    @foreach($childs as $child)
        <input type="checkbox" id="category[]" name="category[]" disabled
        @if (!Request::is('admin/products/create'))
        value="{{ $child->id }}"
        class="list"
        {{ (in_array($child->id,@$product_category_ids)  || (is_array(old('list')) && in_array($child->id,old('list')))) ? "checked": ''  }}
        @else
        value="{{ $child->id }}" class="list" {{ (is_array(old('list')) && in_array($pm->id,old('list'))) ? "checked": ''  }}
        @endif

        > {{$child->category_name}}
        @if(count($child->sub_category))
            @include('admin.products.manage_child_checkbox_show',[
                'childs' =>
                    $child->sub_category,
                "category" => @$product_category_ids,
            ])
        @else
             <br>
        @endif
    @endforeach
</ul>

