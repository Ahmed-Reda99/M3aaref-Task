<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M3aarf Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Tajawal';
            background: rgb(231, 230, 230);
        }

        .bg-blue {
            background-image: linear-gradient(to left, #09203a, #04101d);
        }

        #main-container {
            width: 90%;
        }

        #formContainer{
            margin-top: -20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .rounded-5 {
            border-radius: 15px;
        }

        .custom-tabs {
            gap: 10px;
        }

        .custom-tabs .nav-link {
            background-color: #fff;
            color: grey;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
        }

        .custom-tabs .nav-link.active {
            background-color: #dc3545;
            color: #fff;
        }

        .custom-tabs .nav-link:hover {
            background-color: #dc3545;
            color: #fff
        }

        .custom-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .custom-card img {
            max-height: 300px;
            object-fit: cover;
        }

        .fs-14 {
            font-size: 14px;
        }

        /* Pagination wrapper */
        .pagination {
            gap: 10px;
        }

        /* Default page items */
        .page-item .page-link {
            border: none;
            border-radius: 12px;
            background-color: #f5f5f5;
            color: #555;
            padding: 10px 14px;
            min-width: 40px;
            text-align: center;
            transition: all 0.2s ease-in-out;
        }

        /* Hover effect */
        .page-item .page-link:hover {
            background-color: #eaeaea;
            color: #000;
        }

        /* Active page */
        .page-item.active .page-link {
            background-color: #e74c3c; /* red */
            color: #fff;
            font-weight: 600;
        }

        /* Disabled buttons (prev/next) */
        .page-item.disabled .page-link {
            background-color: rgb(240, 240, 240, .7);
            color: #817b7b;
        }

        /* Remove focus outline */
        .page-link:focus {
            box-shadow: none;
        }

    </style>
</head>
<body class="d-flex flex-column align-items-center">
    <div class="w-100 bg-white">
        <div class="d-flex align-items-center px-5 fw-bold">
            <img src="{{ asset('youtube.webp') }}" alt="Youtube Logo" style="width: 50px; height: 50px;">
            <span class="text-danger">YouTube Course Scraper</span>
            <span class="border-end pe-2 me-2 text-muted fs-14">أداة جمع الدورات التعليمية</span>
        </div>
        <div class="bg-blue p-5">
            <h2 class="text-white text-bold">
                جمع الدورات التعليمية من يوتيوب
            </h2>
            <span class="text-muted fs-14">أدخل التصنيفات واضغط ابدأ - النظام سيجمع الدورات تلقائياً باستخدام الذكاء الاصطناعي</span>
        </div>
    </div>
    <div id="main-container">
        <div class="bg-white rounded-5 w-100 p-4" id="formContainer">
            <form class="row" id="scrapeForm">
                <div class="col-12 col-lg-9">
                    <h6 for="categories" class="text-muted fw-bold fs-14">أدخل التصنيفات (كل تصنيف في سطر جديد)</h6>
                    <textarea class="form-control" id="categories_inp" name="categories" rows="5">{{ implode("\n", $categories) }}</textarea>
                </div>
                <div class="col-12 col-lg-3 d-flex flex-column align-items-start justify-content-end">
                    <button type="button" id="startButton" class="btn btn-danger text-white w-100 mb-2 mt-2 mt-lg-0">
                        <i class="fas fa-play"></i>
                        ابدأ الجمع
                    </button>
                    <button type="button" id="pauseButton" class="btn border border-2 text-muted w-100" disabled>
                        <i class="fas fa-square"></i>
                        إيقاف
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-5">
            <h4 class="fw-bolder">الدورات المكتشفة</h4>
            @php
                $playlistsCount = $categoriesTabs->sum('playlists_count');
                $categoriesCount = count($categories);
                $categoriesQuery = implode(',', $categories);
            @endphp
            <span class="text-muted fs-14">تم العثور على {{ $playlistsCount }} دورة في {{ $categoriesCount }} تصنيفات</span>

            <div class="d-flex justify-content-end">
                <ul class="nav custom-tabs fs-14" id="courseTabs" role="tablist">
                    <a class="text-decoration-none" href="/?categories={{ $categoriesQuery }}">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $selectedCategory == null ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                            الكل ({{ $playlistsCount }})
                            </button>
                        </li>
                    </a>
                    @foreach ($categoriesTabs as $categoriesTab)
                        <a class="text-decoration-none" href="/?categories={{ $categoriesQuery }}&selected_category={{ $categoriesTab->category }}">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $selectedCategory == $categoriesTab->category ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#{{ $categoriesTab->category }}" type="button" role="tab">
                                    {{ $categoriesTab->category }} ({{ $categoriesTab->playlists_count }})
                                </button>
                            </li>
                        </a>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content mt-3 mb-5">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="row g-4">
                        @forelse ($playlists as $playlist)
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3 mb-lg-0 d-flex">
                                <div class="card custom-card d-flex flex-column rounded-5 ">
                                    <div class="position-relative">
                                        <img src="{{ $playlist->thumbnail ?? asset('preview_image.jpg') }}" class="card-img-top" alt="Course Image">
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="border-radius: 50px;">
                                            18 درس
                                        </span>
                                        <span class="badge position-absolute bottom-0 start-0 m-2" style="background: #000">
                                            3 ساعات 25 دقيقة
                                        </span>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold">{{ $playlist->title }}</h5>
                                        <p class="card-text text-muted fs-14 mb-0">
                                            {{ $playlist->description }}
                                        </p>

                                        <div class="mt-auto">
                                            <hr>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge text-danger fw-bold" style="border-radius: 50px; background-color: #ffcbd0;">{{ $playlist->category }}</span>
                                                <span class="text-secondary fs-14">
                                                    <i class="far fa-user"></i>
                                                    {{ $playlist->channel_name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12" id="emptyContainer">
                                <h4 class="text-center text-muted">لا يوجد دورات, ابدأ الجمع</h4>
                            </div>
                        @endforelse
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $playlists->appends(request()->query())->links() }}
                    </div>
                </div>
                {{-- @foreach ($categories as $category)
                    <div class="tab-pane fade" id="{{ $category }}" role="tabpanel">
                        <div class="row g-4">
                            @foreach ($playlists->where('category', $category) as $playlist)
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-3 mb-lg-0">
                                    <div class="card custom-card rounded-5">
                                        <div class="position-relative">
                                            <img src="{{ $playlist->thumbnail ?? asset('preview_image.jpg') }}" class="card-img-top" alt="Course Image">
                                            <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="border-radius: 50px;">
                                                18 درس
                                            </span>
                                            <span class="badge position-absolute bottom-0 start-0 m-2" style="background: #000">
                                                3 ساعات 25 دقيقة
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title fw-bold">{{ $playlist->title }}</h5>
                                            <p class="card-text text-muted fs-14">
                                                {{ $playlist->description }}
                                            </p>

                                            <hr>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge text-danger fw-bold" style="border-radius: 50px; background-color: #ffcbd0;">{{ $category }}</span>
                                                <span class="text-secondary fs-14">
                                                    <i class="far fa-user"></i>
                                                    {{ $playlist->channel_name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let startButton = $("#startButton");
        let pauseButton = $("#pauseButton");
        let categoriesInput = $("#categories_inp");
        let isScraping = false;
        let emptyContainer = $("#emptyContainer");

        startButton.on("click", function() {
            let cleanedCategories = categoriesInput.val()
                                    .split("\n")
                                    .map(line => line.replace(/[^\p{L}\p{N} ]+/gu, '')) // remove special chars, keep letters/numbers/spaces
                                    .map(c => c.trim())
                                    .filter(c => c.length > 0);

            if(cleanedCategories.length === 0) {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: "الرجاء إدخال تصنيفات صحيحة (كل تصنيف في سطر جديد).",
                    timer: 2000
                });
                return;
            }

            if (!isScraping) {
                isScraping = true;
                startButton.prop("disabled", true);
                pauseButton.prop("disabled", false);
                categoriesInput.prop("disabled", true);

                emptyContainer.html(`
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-danger" role="status" style="width: 100px; height: 100px;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-muted mt-2">جاري جمع الدورات التعليمية...</span>
                    </div>
                `);


                $.ajax({
                    url: "{{ route('fetch') }}",
                    type: "POST",
                    data: {
                        categories: cleanedCategories
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        const categories = response.categories;

                        // convert to query string
                        const query = categories.join(',');

                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Playlists fetched successfully.",
                            timer: 2000
                        })
                        .then((result) => {
                            window.location.href = '/?categories=' + query;
                        })
                    },
                    error: function(xhr, status, error) {
                        let message = "An error occurred.";

                        try {
                            const response = JSON.parse(xhr.responseText);

                            // Laravel wrapper
                            if (response.message) {
                                message = response.message;
                            }

                            // Google API nested error
                            if (response.error && response.error.errors?.length) {
                                message = response.error.errors[0].message;
                            }

                            // Remove HTML tags like <a>
                            message = message.replace(/<[^>]*>/g, '');

                        } catch (e) {
                            message = xhr.responseText || error;
                        }

                        Swal.fire({
                            icon: "error",
                            title: error || "An error occurred while fetching playlists.",
                            text: message || "Please try again later.",
                            showConfirmButton: true
                        });
                    },
                    complete: function() {
                        startButton.prop("disabled", false);
                        pauseButton.prop("disabled", true);
                        categoriesInput.prop("disabled", false);
                        isScraping = false;
                        emptyContainer.html('<h4 class="text-center text-muted">لا يوجد دورات, ابدأ الجمع</h4>');
                    }
                });
            }
        })
    </script>
</body>
</html>
