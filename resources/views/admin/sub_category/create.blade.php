@extends('admin.layouts.app')

@section('content')
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" name="subCategory" id="subCategoryForm" method="post">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Category</label>
                                <select name="category" id="category" class="form-control">
                                    @if($categories->isNotEmpty())
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showHome">Show on Hom</label>
                                <select name="showHome" id="showHome" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#subCategoryForm").submit(function (event) {
            event.preventDefault();
            var element = $("#subCategoryForm");
            $("button[type=submit]").prop('disabled',true);
            $.ajax({
                url: '{{ route('sub-categories.store') }}', // Replace with your route
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function (response) {
                    $("button[type=submit]").prop('disabled',false);
                    if(response['status'] == true){
                        window.location.href="{{route('sub-categories.index')}}"

                        $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

                        $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

                        $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

                    }else{

                        if(response['notFound'] == true){
                            window.location.href="{{route('sub-categories.index')}}"
                            return false;
                        }

                        var errors = response['errors']
                        if(errors['name']){
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                        }else{
                            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }

                        if(errors['slug']){
                            $('#slug ').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                        }else{
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }

                        if(errors['category']){
                            $('#category ').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                        }else{
                            $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }
                    }
                },
                error: function (jqXHR,exception) {
                    console.log('Unexpected error:'); // Log unexpected errors
                }
            });
        });

        $('#name').change(function (){
            element = $(this);
            $("button[type=submit]").prop('disabled',true);
            $.ajax({
                url: '{{ route('getSlug') }}', // Replace with your route
                type: 'get',
                data: {title:element.val()},
                dataType: 'json',
                success: function (response) {
                    $("button[type=submit]").prop('disabled',false);
                    if(response['status'] == true){
                        $('#slug').val(response['slug'])
                    }
                }
            })
        });
    </script>
@endsection
