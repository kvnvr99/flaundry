<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            @include('component.breadcrumb')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form id="form-save-update" action="{{ $action }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method($method)
                                        {{ $content }}
                                        @if ($action != '#')
                                            <button type="submit" class="btn btn-primary btn-submit waves-effect waves-light">Submit</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
