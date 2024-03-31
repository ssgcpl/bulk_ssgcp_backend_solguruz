<ul>
    @foreach($childs as $child)
    <input type="checkbox" id="category[]" name="category[]"
    @if (!Request::is('admin/coupons/create'))
    value="{{ $child->id }}"
    class="list"
    {{ (in_array($child->id,$coupon_category_ids)  || (is_array(old('list')) && in_array($child->id,old('list')))) ? "checked": ''  }}
    @else
    value="{{ $child->id }}" class="list" {{ (is_array(old('list')) && in_array($pm->id,old('list'))) ? "checked": ''  }}
    @endif
    >{{$child->category_name}}
        @if(count($child->sub_category))
            @include('admin.coupons.manage_checkbox',[
                'childs' =>
                    $child->sub_category,
                "category" => @$coupon_category_ids,
            ])
        @else
             <br>
        @endif
    @endforeach
</ul>

