<?php

namespace App\Services\Common;

use App\Enum\AdviceStatusEnum;
use App\Enum\EventStatusEnum;
use App\Enum\NewsStatusEnum;
use App\Models\Advice;
use App\Models\Event;
use App\Models\News;

class BlogService
{
    public function find_all_by_type($type)
    {
        if ($type == 'news') {
            return $this->get_show_in_list_news();
        }
        if ($type == 'events') {
            return $this->get_show_in_list_events();
        }
        if ($type == 'advices') {
            return $this->get_show_in_list_advices();
        }
    }

    public function find_blog_by_type_and_slug($type, $slug)
    {
        if ($type == 'news') {
            return $this->get_news_by_slug($slug);
        }
        if ($type == 'events') {
            return $this->get_event_by_slug($slug);
        }
        if ($type == 'advices') {
            return $this->get_advice_by_slug($slug);
        }
    }

    private function get_show_in_list_news()
    {
        return News::with('category')->whereIn('status', [NewsStatusEnum::SHOW_IN_HOME, NewsStatusEnum::SHOW_IN_LIST])
            ->orderBy('id', 'DESC')->paginate(9);
    }

    private function get_show_in_list_events()
    {
        return Event::whereIn('status', [EventStatusEnum::SHOW_IN_HOME, EventStatusEnum::SHOW_IN_LIST])
            ->orderBy('id', 'DESC')->paginate(9);
    }

    private function get_show_in_list_advices()
    {
        return Advice::whereIn('status', [AdviceStatusEnum::SHOW_IN_HOME, AdviceStatusEnum::SHOW_IN_LIST])
            ->orderBy('id', 'DESC')->paginate(9);
    }

    private function get_news_by_slug($slug): array
    {
        $meta_title = "";
        $meta_desc = "";
        $news = News::with('creator')->whereIn('status', [NewsStatusEnum::SHOW_IN_HOME, NewsStatusEnum::SHOW_IN_LIST])
            ->where('slug', $slug)->first();
        $pattern = '/\[button_shortcode(.*?)\]/';
        if ($news) {
            $outputString = preg_replace_callback($pattern, [$this, 'replace_short_code'], $news->newsdetail);
            $news->newsdetail = $outputString;
            $meta_title = "News, {$news->title} | Blog";
            $meta_desc = "{$news->title} is a" . strip_tags($news->newsdetail);
        }
        return [
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
            'blog'          => $news,
            'similar'       => News::with('creator')->whereIn('status', [NewsStatusEnum::SHOW_IN_HOME, NewsStatusEnum::SHOW_IN_LIST])
                ->inRandomOrder()->take(2)->get()
        ];
    }

    private function get_event_by_slug($slug): array
    {
        $meta_title = "";
        $meta_desc = "";
        $event = Event::with('creator')->whereIn('status', [EventStatusEnum::SHOW_IN_HOME, EventStatusEnum::SHOW_IN_LIST])
            ->where('slug', $slug)->first();
        $pattern = '/\[button_shortcode(.*?)\]/';
        if ($event) {
            $outputString = preg_replace_callback($pattern, [$this, 'replace_short_code'], $event->details);
            $event->details = $outputString;
            $meta_title = "Event, {$event->title} | Blog";
            $meta_desc = "{$event->title} is a" . strip_tags($event->details);
        }
        return [
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
            'blog'          => $event,
            'similar'       => Event::with('creator')
                ->whereIn('status', [EventStatusEnum::SHOW_IN_HOME, EventStatusEnum::SHOW_IN_LIST])
                ->inRandomOrder()->take(2)->get()
        ];
    }

    private function get_advice_by_slug($slug): array
    {
        $meta_title = "";
        $meta_desc = "";
        $advice = Advice::with('creator')->whereIn('status', [AdviceStatusEnum::SHOW_IN_HOME, AdviceStatusEnum::SHOW_IN_LIST])
            ->where('slug', $slug)->first();
        $pattern = '/\[button_shortcode(.*?)\]/';
        if ($advice) {
            $outputString = preg_replace_callback($pattern, [$this, 'replace_short_code'], $advice->details);
            $advice->details = $outputString;
            $meta_title = "Advice, {$advice->title} | Blog";
            $meta_desc = "{$advice->title} is a" . strip_tags($advice->details);
        }
        return [
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
            'blog'          => $advice,
            'similar'       => Advice::with('creator')
                ->whereIn('status', [AdviceStatusEnum::SHOW_IN_HOME, AdviceStatusEnum::SHOW_IN_LIST])
                ->inRandomOrder()->take(2)->get()
        ];
    }

    private function replace_short_code($matches)
    {
        $title = '';
        $link = '';

        if (isset($matches[1])) {
            preg_match('/title="(.*?)"/', $matches[1], $titleMatch);
            $title = isset($titleMatch[1]) ? $titleMatch[1] : '';

            preg_match('/link="(.*?)"/', $matches[1], $linkMatch);
            $link = isset($linkMatch[1]) ? $linkMatch[1] : '';
        }

        $button = '<a class="btn my-3 p-2 text-center" type="button" style="min-width: 150px;background: var(--red-color); color: whitesmoke" href="https://' . $link . '" target="_blank">' . $title . ' </a>';

        return $button;
    }
}
