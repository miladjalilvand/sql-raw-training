<li data-id="{{ $task->id }}">
    {{ $task->caption }}
    <button class="delete-task" data-id="{{ $task->id }}" style="color: red; margin-left: 10px; background: none; border: none; cursor: pointer;">حذف</button>
</li>