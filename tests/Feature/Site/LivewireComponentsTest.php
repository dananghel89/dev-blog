<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Livewire\Livewire;

/*
 * The Livewire components behind the blog lists and the search page (Blogy theme plan, Phase 6).
 */

test('the blog pages render the post-list component', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();

    $this->get(route('blog.index'))->assertOk()->assertSeeLivewire('blog.post-list');
    $this->get(route('blog.category', $category->slug))->assertOk()->assertSeeLivewire('blog.post-list');
    $this->get(route('blog.tag', $tag->slug))->assertOk()->assertSeeLivewire('blog.post-list');
    $this->get(route('blog.search'))->assertOk()->assertSeeLivewire('blog.search');
});

test('post-list shows only published posts, newest first', function () {
    $older = Post::factory()->published()->create(['title' => 'Zebracorn Older', 'published_at' => now()->subDays(2)]);
    $newer = Post::factory()->published()->create(['title' => 'Zebracorn Newer', 'published_at' => now()->subDay()]);
    $draft = Post::factory()->unpublished()->create(['title' => 'Zebracorn Draft']);

    Livewire::test('blog.post-list')
        ->assertSeeInOrder([$newer->title, $older->title])
        ->assertDontSee($draft->title);
});

test('post-list filters by category and by tag', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();

    $inCategory = Post::factory()->published()->create(['title' => 'Zebracorn In Category', 'category_id' => $category->id]);
    $tagged = Post::factory()->published()->create(['title' => 'Zebracorn Tagged']);
    $tagged->tags()->attach($tag);
    $other = Post::factory()->published()->create(['title' => 'Zebracorn Other']);

    Livewire::test('blog.post-list', ['category' => $category])
        ->assertSee($inCategory->title)
        ->assertDontSee($tagged->title)
        ->assertDontSee($other->title);

    Livewire::test('blog.post-list', ['tag' => $tag])
        ->assertSee($tagged->title)
        ->assertDontSee($inCategory->title)
        ->assertDontSee($other->title);
});

test('post-list paginates by 10 and can go to page 2', function () {
    $posts = collect(range(1, 12))->map(fn (int $i) => Post::factory()->published()->create([
        'title' => sprintf('Paged Post %02d', $i),
        'published_at' => now()->subMinutes(100 - $i),
    ]));

    Livewire::test('blog.post-list')
        ->assertSee('Paged Post 12')
        ->assertDontSee('Paged Post 01')
        ->call('gotoPage', 2)
        ->assertSee('Paged Post 01')
        ->assertDontSee('Paged Post 12');
});

test('post-list shows the empty message when there are no posts', function () {
    Livewire::test('blog.post-list', ['emptyMessage' => 'Nothing here yet.'])
        ->assertSee('Nothing here yet.');
});

test('search reads the query from the URL and updates when typing', function () {
    Post::factory()->published()->create(['title' => 'Zebracorn Search Hit']);
    Post::factory()->published()->create(['title' => 'Quokkafish Search Hit']);

    Livewire::withQueryParams(['q' => 'Zebracorn'])
        ->test('blog.search')
        ->assertSet('q', 'Zebracorn')
        ->assertSee('Zebracorn Search Hit')
        ->assertDontSee('Quokkafish Search Hit')
        ->set('q', 'Quokkafish')
        ->assertSee('Quokkafish Search Hit')
        ->assertDontSee('Zebracorn Search Hit');
});

test('search needs at least two characters', function () {
    Post::factory()->published()->create(['title' => 'A Zebracorn Post']);

    Livewire::test('blog.search')
        ->set('q', 'Z')
        ->assertSee('Type at least 2 characters')
        ->assertDontSee('A Zebracorn Post');
});

test('search treats % and _ literally', function () {
    Post::factory()->published()->create(['title' => 'An Ordinary Post']);
    Post::factory()->published()->create(['title' => 'Save 100% Today']);

    Livewire::test('blog.search')
        ->set('q', '0%')
        ->assertSee('Save 100% Today')
        ->assertDontSee('An Ordinary Post');

    Livewire::test('blog.search')
        ->set('q', '%%')
        ->assertSee('0 results')
        ->assertDontSee('An Ordinary Post');
});

test('search never finds drafts', function () {
    Post::factory()->unpublished()->create(['title' => 'Zebracorn Secret Draft']);

    Livewire::test('blog.search')
        ->set('q', 'Zebracorn')
        ->assertDontSee('Zebracorn Secret Draft')
        ->assertSee('0 results');
});
