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
<div class="mb-3">
    <label class="form-label">Replacement Price (₱)</label>
    <input type="number" step="0.01" min="0" name="replacement_price"
           class="form-control" value="{{ old('replacement_price', $book->replacement_price ?? 0) }}">
    <div class="form-text">Used to calculate the fine if this book is reported lost.</div>
</div>