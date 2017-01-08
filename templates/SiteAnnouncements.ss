<% if $PAMessages %>
	<% loop $PAMessages %>
		<div class="ss_announcement__message-outer ss_announcement--{$ForCSS($PagePos)} ss_announcement__takes-space--{$TakesSpace}" id="ss_announcement--{$ForCSS($Title)}" style="background-color: <% if $BackgroundTransparency %>{$BackgroundColor.CSSColor(0.5)}<% else %>#{$BackgroundColor}<% end_if %>; position:<% if $StickyPos %>fixed<% else %>absolute<% end_if %>;">
			<div class="ss_announcement__content-container">

				<div class="ss_announcement__top">
					<span class="ss_announcement__title" style="color:#{$TextColor}">{$Title}</span>
					<% if CanClose %>
						<div class="ss_announcement__cross-holder">
							<div class="ss_announcement__cross" title="Close announcement" onclick="messageAction.removeMessage(this)"></div>
						</div>
					<% end_if %>
				</div>
				<div class="ss_announcement__info-holder">
					<span class="ss_announcement__content" style="color:#{$TextColor}">{$Content}</span>
				</div>
				<% if HasCTA %>
					<div class="ss_announcement__cta-holder">
						<a href="{$CTALink}" style="background-color:#{$CTAColor}!important;" <% if $LinkInNewTab %> target="_blank"<% end_if %> class="ss_announcement__cta">
							<span class="ss_announcement__cta-text" style="color:#{$CTATextColor}!important;background-color:{$CTAColor}!important">{$CTAText}</span>
						</a>
					</div>
				<% end_if %>

			</div>
		</div>
	<% end_loop %>
<% end_if %>
