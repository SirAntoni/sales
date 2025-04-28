@props(['as' => 'span', 'content' => null, 'placement' => 'top'])

@if (substr($as, 0, 2) == 'x-')
    <x-dynamic-component
        data-placement="{{ $placement }}"
        title="{!! $content !!}"
        data-original-title="{!! $content !!}"
        {{ $attributes->class(merge(['tooltip cursor-pointer', $attributes->whereStartsWith('class')->first()]))->merge($attributes->whereDoesntStartWith('class')->getAttributes()) }}
        :component="substr($as, 2)"
    >{{ $slot }}</x-dynamic-component>
@else
    @if ($as == 'img')
        <{{ $as }}
            data-placement="{{ $placement }}"
        title="{!! $content !!}"
        {{ $attributes->class(merge(['tooltip cursor-pointer', $attributes->whereStartsWith('class')->first()]))->merge($attributes->whereDoesntStartWith('class')->getAttributes()) }}
        >
    @else
        <{{ $as }}
            data-placement="{{ $placement }}"
        title="{!! $content !!}"
        {{ $attributes->class(merge(['tooltip cursor-pointer', $attributes->whereStartsWith('class')->first()]))->merge($attributes->whereDoesntStartWith('class')->getAttributes()) }}
        >{{ $slot }}</{{ $as }}>
    @endif
@endif

@pushOnce('styles')
    @vite('resources/css/vendors/tippy.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/tippy.js')
@endPushOnce

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('reinit-tippy', event => {
               setTimeout(() => {

                   $(".tooltip").each(function () {

                       if(this._tippy){
                           this._tippy.destroy();

                       }

                       let content =  $(this).attr("data-original-title");
                       let options = {
                           content: content,
                       };

                       if ($(this).data("trigger") !== undefined) {
                           options.trigger = $(this).data("trigger");
                       }

                       if ($(this).data("placement") !== undefined) {
                           options.placement = $(this).data("placement");
                       }

                       if ($(this).data("theme") !== undefined) {
                           options.theme = $(this).data("theme");
                       }

                       if ($(this).data("tooltip-content") !== undefined) {
                           options.content = $($(this).data("tooltip-content"))[0];
                       }


                       $(this).removeAttr("title");

                       tippy(this, {
                           arrow: roundArrow,
                           allowHTML: true,
                           animation: "shift-away",
                           ...options,
                       });
                   });

               },100);


            });
        });

    </script>
@endPushOnce
