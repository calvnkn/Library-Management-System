<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $book->title ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Author</label>
    <input type="text" name="author" class="form-control" value="{{ old('author', $book->author ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">ISBN</label>
    <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Publication</label>
    <input type="text" name="publication" class="form-control" value="{{ old('publication', $book->publication ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Category/Genre</label>
    <input type="text" name="category" class="form-control" value="{{ old('category', $book->category ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Total Copies</label>
    <input type="number" name="total_copies" class="form-control" value="{{ old('total_copies', $book->total_copies ?? 1) }}">
</div>