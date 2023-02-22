@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.update',$product->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{$product->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
          <textarea class="form-control"  name="summary">{{$product->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control"  name="description">{{$product->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>


        <div class="form-group">
          <label for="is_featured">Is Featured</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='{{$product->is_featured}}' {{(($product->is_featured) ? 'checked' : '')}}> Yes
        </div>
              {{-- {{$categories}} --}}

        <div class="form-group">
          <label for="cat_id">Category <span class="text-danger">*</span></label>
          <select name="cat_id" id="cat_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($categories as $key=>$cat_data)
                  <option value='{{$cat_data->id}}' {{(($product->cat_id==$cat_data->id)? 'selected' : '')}}>{{$cat_data->title}}</option>
              @endforeach
          </select>
        </div>
        @php
          $sub_cat_info=DB::table('categories')->select('title')->where('id',$product->child_cat_id)->get();
        // dd($sub_cat_info);

        @endphp
        {{-- {{$product->child_cat_id}} --}}
        <div class="form-group {{(($product->child_cat_id)? '' : 'd-none')}}" id="child_cat_div">
          <label for="child_cat_id">Sub Category</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--Select any sub category--</option>

          </select>
        </div>

          <div class="form-group {{(($product->child_sub_cat_id)? '' : 'd-none')}}" id="child_sub_cat_div">
              <label for="child_cat_id">Sub Sub Category</label>
              <select name="child_sub_cat_id" id="child_sub_cat_id" class="form-control">
                  <option value="">--Select any sub sub category--</option>

              </select>
          </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Price(NRS) <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter price"  value="{{$product->price}}" class="form-control">
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="discount" class="col-form-label">Discount(%)</label>
          <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount"  value="{{$product->discount}}" class="form-control">
          @error('discount')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="stock">Quantity in stock<span class="text-danger">*</span></label>
          <input id="quantity" type="number" name="quantity_in_stock" min="0" placeholder="Enter quantity"  value="{{$product->quantity_in_stock}}" class="form-control">
          @error('stock')
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
              <br>
              @if($product->photo)
                  <img style="max-height: 50px" src="{{asset($product->photo)}}">
              @else
                  <span style="color: red">No Image uploaded</span>
              @endif
              @error('photo')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>

          <div class="form-group">
              <label for="suggested_prod_id">Products for suggested list</label>
              <select name="suggested_prod_id[]" id="cat_id" class="form-control" multiple>
                  <option value="">--Select products--</option>
                  @php
                  $product_ids = explode(',',$product->suggested_prod_id);
                  @endphp
                  @foreach($products as $key=>$product_detail)
                      <option {{((in_array($product_detail->id,$product_ids) )? 'selected' : '')}} value='{{$product_detail->id}}'>{{$product_detail->title}}</option>
                  @endforeach
              </select>
          </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="active" {{(($product->status=='active')? 'selected' : '')}}>Active</option>
            <option value="inactive" {{(($product->status=='inactive')? 'selected' : '')}}>Inactive</option>
        </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
          <div class="form-group">
              <label for="status" class="col-form-label">Show Calculator <span class="text-danger">*</span></label>
              <select name="calculator_show" class="form-control calculator_show">
                  <option value="1" {{(($product->calculator_show==1)? 'selected' : '')}}>Yes</option>
                  <option value="2" {{(($product->calculator_show==2)? 'selected' : '')}}>No</option>
              </select>
              @error('calculator_show')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
          <div style="display: {{(($product->calculator_show == 1)? 'block' : 'none')}}" class="form-group calculator_type">
              <label for="status" class="col-form-label">Calculator Type<span class="text-danger">*</span></label>
              <select name="calculator_type" class="form-control">
                  <option value="0" {{(($product->calculator_type==0)? 'selected' : '')}}>Select Type</option>
                  <option value="1" {{(($product->calculator_type==1)? 'selected' : '')}}>Custom</option>
                  <option value="2" {{(($product->calculator_type==2)? 'selected' : '')}}>Box</option>
                  <option value="3" {{(($product->calculator_type==3)? 'selected' : '')}}>Role</option>
              </select>
          </div>
          <div class="form-group">
              <label for="inputWidth" class="col-form-label">Width <span class="text-danger">*</span></label>
              <input id="inputWidth" type="text" name="width" placeholder="Enter Width" value="{{$product->width}}" class="form-control">
              @error('width')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
          <div class="form-group">
              <label for="inputHeight" class="col-form-label">Height <span class="text-danger">*</span></label>
              <input id="inputHeight" type="text" name="height" placeholder="Enter height" value="{{$product->height}}" class="form-control">
              @error('height')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
          <div class="form-group">
              <label for="inputTitle" class="col-form-label">Meter Per Box <span class="text-danger">*</span></label>
              <input id="inputTitle" type="text" name="meter_per_box" placeholder="Enter title" value="{{$product->meter_per_box}}" class="form-control">
              @error('meter_per_box')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
          <div class="form-group">
              <label for="inputTitle" class="col-form-label">Minimum Qty<span class="text-danger">*</span></label>
              <input id="inputTitle" type="text" name="minimum_qty" placeholder="Enter Minimum Qty for order" value="{{$product->minimum_qty}}" class="form-control">
              @error('minimum_qty')
              <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
          <div class="form-group">
              <label for="inputTitle" class="col-form-label">Reached limit error</label>
              <input id="inputTitle" type="text" name="reached_limit_error" placeholder="Enter Reached limit error" value="{{$product->reached_limit_error}}" class="form-control">
          </div>
          <div class="form-group">
              <label for="expiration_date">Expiration Date </label>
              <input id="expiration_date" type="date" name="expiration_date"  value="{{$product->expiration_date}}" class="form-control">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('.calculator_show').on('change', function(){
        var ca_show = $(this).val();
        if (ca_show === '1') {
            $('.calculator_type').css('display', 'block');
        } else {
            $('.calculator_type').css('display', 'none');
        }
    });
    $('#lfm').filemanager('image');

    $(document).ready(function() {
        var ca_show = $('.calculator_show').val();
        if (ca_show === '1') {
            $('.calculator_type').css('display', 'block');
        } else {
            $('.calculator_type').css('display', 'none');
        }
    $('#summary').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Write detail Description.....",
          tabsize: 2,
          height: 150
      });
    });
</script>

<script>
  var  child_cat_id='{{$product->child_cat_id}}';
        // alert(child_cat_id);
        $('#cat_id').change(function(){
            var cat_id=$(this).val();

            if(cat_id !=null){
                // ajax call
                $.ajax({
                    url:"/admin/category/"+cat_id+"/child",
                    type:"POST",
                    data:{
                        _token:"{{csrf_token()}}"
                    },
                    success:function(response){
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        var html_option="<option value=''>--Select any one--</option>";
                        if(response.status){
                            var data=response.data;
                            if(response.data){
                                $('#child_cat_div').removeClass('d-none');
                                $.each(data,function(id,title){
                                    html_option += "<option value='"+id+"' "+(child_cat_id==id ? 'selected ' : '')+">"+title+"</option>";
                                });
                            }
                            else{
                                console.log('no response data');
                            }
                        }
                        else{
                            $('#child_cat_div').addClass('d-none');
                        }
                        $('#child_cat_id').html(html_option);

                    }
                });
            }
            else{

            }

        });
        if(child_cat_id!=null){
            $('#cat_id').change();
        }
  var  child_sub_cat_id='{{$product->child_sub_cat_id}}';
  /*alert(child_sub_cat_id);
  alert($('#child_cat_id').val());*/
  $('#child_cat_id').change(function(){
      var child_cat_id='{{$product->child_cat_id}}';
        console.log(child_cat_id);
      if(child_cat_id !=null){
          // ajax call
          $.ajax({
              url:"/admin/child-category/"+child_cat_id+"/sub_child",
              type:"POST",
              data:{
                  _token:"{{csrf_token()}}"
              },
              success:function(response){
                  if(typeof(response)!='object'){
                      response=$.parseJSON(response);
                  }
                  var html_option="<option value=''>--Select any one--</option>";
                  if(response.status){
                      var data=response.data;
                      if(response.data){
                          $('#child_sub_cat_div').removeClass('d-none');
                          $.each(data,function(id,title){
                              html_option += "<option value='"+id+"' "+(child_sub_cat_id==id ? 'selected ' : '')+">"+title+"</option>";
                          });
                      }
                      else{
                          console.log('no response data');
                      }
                  }
                  else{
                      $('#child_sub_cat_div').addClass('d-none');
                  }
                  $('#child_sub_cat_id').html(html_option);

              }
          });
      }
      else{

      }

  });
  if(child_sub_cat_id!=null){
      $('#child_cat_id').change();
  }
</script>
@endpush
