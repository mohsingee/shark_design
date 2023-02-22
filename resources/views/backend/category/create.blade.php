@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Category</h5>
    <div class="card-body">
      <form method="post" action="{{route('category.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary</label>
          <textarea class="form-control" name="summary">{{old('summary')}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="is_parent">Is Parent</label><br>
          <input type="checkbox" name='is_parent' id='is_parent' value='1' checked> Yes
        </div>
        {{-- {{$parent_cats}} --}}

        <div class="form-group" id='parent_cat_divs'>
          <label for="parent_id">Parent Category</label>
          <select id="cat_id" name="parent_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($parent_cats as $key=>$parent_cat)
                  @if($parent_cat->child_cat->count()>0)
                      <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
                      @foreach($parent_cat->child_cat as $child_cat){
                        <option value='{{$child_cat->id}}'>-{{$child_cat->title}}</option>
                      @endforeach
                  @else
                      <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
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
              @error('photo')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<!--<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>-->
<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    var route_prefix = "{{URL::to('/')}}";
    $('#lfm').filemanager('image');

    $(document).ready(function() {
      /*$('#summary').summernote({
        placeholder: "Write short description.....",
          tabsize: 2,
          height: 120
      });*/
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
