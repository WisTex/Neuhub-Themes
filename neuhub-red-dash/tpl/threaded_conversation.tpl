<!-- threaded conversation -->
{{if !$preview }}
<div id="threads-begin"></div>
{{/if}}
{{if $photo_item}}
{{$photo_item}}
{{/if}}
{{foreach $threads as $thread_item}}
{{include file="{{$thread_item.template}}" item=$thread_item}}
{{/foreach}}
{{if !$preview }}
<div id="threads-end"></div>
<div id="conversation-end"></div>
{{/if}}
