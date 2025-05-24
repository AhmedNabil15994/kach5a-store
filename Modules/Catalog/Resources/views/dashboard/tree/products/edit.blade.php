@foreach ($mainCategories as $category)
<ul>
	<li id="{{$category->id}}" data-jstree='{"opened":true
		{{ (isset($product) && $product->categories->contains($category->id)) || (isset($type) && $type == 'create' && $category->id == 1 ) ? ',"selected":true' : ''  }} }'>
		{{$category->title}}
		@if($category->dashboardChildren->count() > 0)
			@include('catalog::dashboard.tree.products.edit',['mainCategories' => $category->dashboardChildren])
		@endif
	</li>
</ul>
@endforeach
