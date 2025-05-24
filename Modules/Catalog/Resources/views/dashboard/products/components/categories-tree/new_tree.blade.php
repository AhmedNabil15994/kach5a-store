
<div id="categories-tree"></div>
<div id="jstree">
    @php $selectedCats = !empty($product) && $product->categories()->count() ? $product->categories->pluck('id')->toArray() : []; @endphp

    @foreach ($mainCategories as $cat)
        <ul>
            <li id="{{$cat->id}}" data-jstree='{"opened":true @if (in_array($cat->id,$selectedCats)),"selected":true @endif }'>
                {{$cat->title}}
                @if($cat->children->count() > 0)
                    @include('catalog::dashboard.products.components.categories-tree.new_tree',['mainCategories' => $cat->children]))
                @endif
            </li>
        </ul>
    @endforeach

</div>

@push('scripts')

    <script>
        {{--let categories = @json($mainCategories);--}}
        {{--let selectedCats = @json($selectedCats);--}}
        {{--console.log(categories)--}}

        $('#jstree').jstree({
            "plugins" : [ "wholerow", "checkbox" ],
            core: {
                multiple: true
            },
            checkbox : {
                "three_state" : false
            }
        });

        $('#jstree').on("changed.jstree", function (e, data) {
            $('#root_category').val(data.selected);
        });






        // prettier-ignore
        // const tree = new dhx.Tree("categories-tree", {
		// 	editable: true,
		// 	dragMode: "both",
		// 	checkbox: true
		// });
		// tree.data.parse(categories);
        //
        // if(selectedCats.length)
        //     selectedCats.map((id) => tree.checkItem(id));
        //
        // tree.events.on('afterSelect', function () {
		// 	const value = tree.getState();
		// 	let filtered = [];
        //
		// 	for(key in value){
		// 		if (value[key].selected != 0) {
		// 			filtered.push(key);
		// 		}
		// 	}
        //     $('#category_id').val(filtered);
		// });
    </script>
@endpush
