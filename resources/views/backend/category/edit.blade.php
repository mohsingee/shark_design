@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Category</h5>
    <div class="card-body">
      <form method="post" action="{{route('category.update',$category->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{$category->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary</label>
          <textarea class="form-control"  name="summary">{{$category->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="is_parent">Is Parent</label><br>
          <input type="checkbox" name='is_parent' id='is_parent' {{(($category->is_parent==1)? 'checked' : '')}}> Yes
        </div>
        {{-- {{$parent_cats}} --}}
        {{-- {{$category}} --}}

      <div class="form-group {{--{{(($category->is_parent==1) ? 'd-none' : '')}}--}}" id='parent_cat_div'>
          <label for="parent_id">Parent Category</label>
          <select id="cat_id" name="parent_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($parent_cats as $key=>$parent_cat)
                  @if($parent_cat->child_cat->count()>0)
                      <option {{(($category->parent_id==$parent_cat->id)? 'selected' : '')}} value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
                      @foreach($parent_cat->child_cat as $child_cat){
                      <option {{(($category->parent_id==$child_cat->id)? 'selected' : '')}} value='{{$child_cat->id}}'>-{{$child_cat->title}}</option>
                      @endforeach
                  @else
                      <option {{(($category->parent_id==$parent_cat->id)? 'selected' : '')}} value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
                  @endif
              @endforeach
          </select>
        </div>
          {{--<div class="form-group d-none" id="child_cat_div">
              <label for="child_cat_id">Sub Category</label>
              <select name="child_cat_id" id="child_cat_id" class="form-control">
                  <option value="">--Select any category--</option>
              </select>
          </div>--}}
          <div class="form-group">
              <label for="inputPhoto" class="col-form-label">Photo</label>
              <div class="input-group">
                  <span class="input-group-btn">
                      <input class="form-control " type="file" name="photo">
                    </span>
              </div>
              <br>
                  @if($category->photo)
                      <img style="max-height: 50px" src="{{asset($category->photo)}}">
                  @else
                      <span style="color: red">No Image uploaded</span>
                  @endif
              @error('photo')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active" {{(($category->status=='active')? 'selected' : '')}}>Active</option>
              <option value="inactive" {{(($category->status=='inactive')? 'selected' : '')}}>Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
    $('#cat_id').change(function() {
        var cat_id = $(this).val();
        // alert(cat_id);
        if (cat_id != null) {
            // Ajax call
            $.ajax({
                url: "/admin/category/" + cat_id + "/child",
                data: {
                    _token: "{{csrf_token()}}",
                    id: cat_id
                },
                type: "POST",
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = $.parseJSON(response)
                    }
                    // console.log(response);
                    var html_option = "<option value=''>----Select sub category----</option>"
                    if (response.status) {
                        var data = response.data;
                        // alert(data);
                        if (response.data) {
                            $('#child_cat_div').removeClass('d-none');
                            $.each(data, function(id, title) {
                                html_option += "<option value='" + id + "'>" + title + "</option>"
                            });
                        } else {}
                    } else {
                        $('#child_cat_div').addClass('d-none');
                    }
                    $('#child_cat_id').html(html_option);
                }
            });
        } else {}
    })
</script>
@endpush
