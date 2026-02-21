<!-- <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<ul id="list-tasks">
    @foreach($tasks as $task)
        @include('part_tasks', ['task' => $task])
    @endforeach
</ul>

<button id="myButton">کلیک کن</button>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#myButton').click(function() {
        $.ajax({
            url: '{{ route("new-task") }}',
            type: 'POST',
            beforeSend: function() {
                $('#myButton').prop('disabled', true).text('در حال ایجاد...');
            },
            success: function(response) {
                // اضافه کردن آیتم جدید به لیست
                $('#list-tasks').append(response.html);
                
                // فعال کردن مجدد دکمه
                $('#myButton').prop('disabled', false).text('کلیک کن');
            },
            error: function(xhr) {
                console.log('خطا:', xhr);
                alert('خطا در ایجاد تسک جدید: ' + (xhr.responseJSON?.message || 'خطای ناشناخته'));
                $('#myButton').prop('disabled', false).text('کلیک کن');
            }
        });
    });
});
</script>

</body>
</html> -->


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<ul id="list-tasks">
    @foreach($tasks as $task)
        @include('part_tasks', ['task' => $task])
    @endforeach
</ul>

<button id="myButton">کلیک کن</button>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ایجاد تسک جدید
    $('#myButton').click(function() {
        $.ajax({
            url: '{{ route("new-task") }}',
            type: 'POST',
            beforeSend: function() {
                $('#myButton').prop('disabled', true).text('در حال ایجاد...');
            },
            success: function(response) {
                $('#list-tasks').append(response.html);
                $('#myButton').prop('disabled', false).text('کلیک کن');
            },
            error: function(xhr) {
                console.log('خطا:', xhr);
                alert('خطا در ایجاد تسک جدید');
                $('#myButton').prop('disabled', false).text('کلیک کن');
            }
        });
    });

    // حذف تسک با متد DELETE
    $(document).on('click', '.delete-task', function(e) {
        e.preventDefault();
        
        var taskId = $(this).data('id');
        var listItem = $(this).closest('li');
        
        if(confirm('آیا از حذف این تسک اطمینان دارید؟')) {
            $.ajax({
                url: '{{ route("task-destroy") }}',
                type: 'DELETE',
                data: {
                    id: taskId
                },
                beforeSend: function() {
                    $(this).text('در حال حذف...');
                },
                success: function(response) {
                    if(response.success) {
                        listItem.fadeOut(300, function() {
                            $(this).remove();
                        });
                        alert('تسک با موفقیت حذف شد');
                    } else {
                        alert('خطا در حذف تسک');
                    }
                },
                error: function(xhr) {
                    console.log('خطا:', xhr);
                    console.log('پیام خطا:', xhr.responseJSON?.message);
                    alert('خطا در حذف تسک: ' + (xhr.responseJSON?.message || 'خطای ناشناخته'));
                }
            });
        }
    });
});
</script>

</body>
</html>