@extends('backend.layouts.master')

@section('main-content')

<div class="card">
  <h5 class="card-header">Add Product</h5>
  <div class="card-body">
    <form method="post" action="{{route('product.store')}}" enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="form-group">
        <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="title" placeholder="Enter title" value="{{old('title')}}" class="form-control">
        @error('title')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
        <textarea class="form-control"  name="summary">{{old('summary')}}</textarea>
        @error('summary')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="description" class="col-form-label">Description</label>
        <textarea class="form-control"  name="description">{{old('description')}}</textarea>
        @error('description')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>


      <div class="form-group">
        <label for="is_featured">Is Featured</label><br>
        <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Yes
      </div>
      {{-- {{$categories}} --}}

      <div class="form-group">
        <label for="cat_id">Category <span class="text-danger">*</span></label>
        <select name="cat_id" id="cat_id" class="form-control">
          <option value="">--Select any category--</option>
          @foreach($categories as $key=>$cat_data)
          <option value='{{$cat_data->id}}'>{{$cat_data->title}}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group d-none" id="child_cat_div">
        <label for="child_cat_id">Sub Category</label>
        <select name="child_cat_id" id="child_cat_id" class="form-control">
          <option value="">--Select any category--</option>
          {{-- @foreach($parent_cats as $key=>$parent_cat)
                  <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
          @endforeach --}}
        </select>
      </div>

    <div class="form-group d-none" id="child_sub_cat_div">
        <label for="child_sub_cat_id">Sub Sub Category</label>
        <select name="child_sub_cat_id" id="child_sub_cat_id" class="form-control">
            <option value="">--Select any category--</option>
            {{-- @foreach($parent_cats as $key=>$parent_cat)
                    <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
            @endforeach --}}
        </select>
    </div>

      <div class="form-group">
        <label for="price" class="col-form-label">Price(NRS) <span class="text-danger">*</span></label>
        <input id="price" type="number" name="price" placeholder="Enter price" value="{{old('price')}}" class="form-control">
        @error('price')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="discount" class="col-form-label">Discount(%)</label>
        <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount" value="{{old('discount')}}" class="form-control">
        @error('discount')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="stock">Quantity in stock<span class="text-danger">*</span></label>
        <input id="quantity" type="number" name="quantity_in_stock" min="0" placeholder="Enter quantity" value="{{old('quantity_in_stock')}}" class="form-control">
        @error('quantity_in_stock')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>
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
            <label for="suggested_prod_id">Products for suggested list</label>
            <select name="suggested_prod_id[]" id="cat_id" class="form-control" multiple>
                <option value="">--Select products--</option>
                @foreach($products as $key=>$product)
                    <option value='{{$product->id}}'>{{$product->title}}</option>
                @endforeach
            </select>
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
        <div class="form-group">
            <label for="status" class="col-form-label">Show Calculator <span class="text-danger">*</span></label>
            <select name="calculator_show" class="form-control calculator_show">
                <option value="1">Yes</option>
                <option value="2">No</option>
            </select>
        </div>
        <div style="display: none" class="form-group calculator_type">
            <label for="status" class="col-form-label">Calculator Type<span class="text-danger">*</span></label>
            <select name="calculator_type" class="form-control">
                <option value="0">Select Type</option>
                <option value="1">Custom</option>
                <option value="2">Box</option>
                <option value="3">Role</option>
            </select>
        </div>
      <div class="form-group">
        <label for="inputWidth" class="col-form-label">Width <span class="text-danger">*</span></label>
        <input id="inputWidth" type="text" name="width" placeholder="Enter Width" value="{{old('width')}}" class="form-control">
          <div class="col-md-12">
              <p>in cm</p>
          </div>
        @error('width')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="inputHeight" class="col-form-label">Length <span class="text-danger">*</span></label>
        <input id="inputHeight" type="text" name="height" placeholder="Enter Length" value="{{old('height')}}" class="form-control">
          <div class="col-md-12">
              <p>in cm</p>
          </div>
        @error('height')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="inputTitle" class="col-form-label">Meter Per Box - Role<span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="meter_per_box" placeholder="Enter title" value="{{old('meter_per_box')}}" class="form-control">
        @error('meter_per_box')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>
        <div class="form-group">
            <label for="inputTitle" class="col-form-label">Minimum Qty<span class="text-danger">*</span></label>
            <input id="inputTitle" type="text" name="minimum_qty" placeholder="Enter Minimum Qty for order" value="{{old('minimum_qty')}}" class="form-control">
            @error('minimum_qty')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="inputTitle" class="col-form-label">Reached limit error</label>
            <input id="inputTitle" type="text" name="reached_limit_error" placeholder="Enter Reached limit error" value="{{old('reached_limit_error')}}" class="form-control">
        </div>
        <div class="form-group">
            <label for="expiration_date">Expiration Date </label>
            <input id="expiration_date" type="date" name="expiration_date"  value="{{old('expiration_date')}}" class="form-control">
        </div>
      <div>
        <h1>Message</h1>

        @if (Session::get('error'))
        <div class="alert alert-warning">
          @if(is_array(Session::get('error')))
          <ul>
            @foreach(Session::get('error') as $msg)
            <li>{!! $msg !!}</li>
            @endforeach
          </ul>
          @else
          {!! Session::get('error') !!}
          @endif
        </div>
        @endif
        @if(Session::has('error'))
        <div class="">
          {{ Session::get('error') }}
        </div>
        @endif

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
  $('#lfm').filemanager('image');

  $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "Write short description.....",
      tabsize: 2,
      height: 100
    });
  });

  $(document).ready(function() {
      var ca_show = $('.calculator_show').val();
      if (ca_show === '1') {
          $('.calculator_type').css('display', 'block');
      } else {
          $('.calculator_type').css('display', 'none');
      }
    $('#description').summernote({
      placeholder: "Write detail description.....",
      tabsize: 2,
      height: 150
    });
  });
  // $('select').selectpicker();
</script>

<script>
    $('.calculator_show').on('change', function(){
        var ca_show = $(this).val();
        if (ca_show === '1') {
            $('.calculator_type').css('display', 'block');
        } else {
            $('.calculator_type').css('display', 'none');
        }
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
  $('#child_cat_id').change(function() {
    var cat_id = $(this).val();
    // alert(cat_id);
    if (cat_id != null) {
      // Ajax call
      $.ajax({
        url: "/admin/child-category/" + cat_id + "/sub_child",
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
          var html_option = "<option value=''>----Select sub sub category----</option>"
          if (response.status) {
            var data = response.data;
            // alert(data);
            if (response.data) {
              $('#child_sub_cat_div').removeClass('d-none');
              $.each(data, function(id, title) {
                html_option += "<option value='" + id + "'>" + title + "</option>"
              });
            } else {}
          } else {
            $('#child_sub_cat_div').addClass('d-none');
          }
          $('#child_sub_cat_id').html(html_option);
        }
      });
    } else {}
  })
</script>
@endpush
