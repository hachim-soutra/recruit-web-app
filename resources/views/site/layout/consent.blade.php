 @if (Cookie::get('consent') === null)
     <div class="seopress-user-consent flex-column flex-md-row">
         <p class="mb-3 mb-md-0">By visiting our site, you agree to our <a target="_blank"
                 href="{{ route('privacy') }}">privacy policy</a>
             regarding cookies,
             tracking statistics, etc.</p>
         <p>
             <a href="{{ route('accept-consent', ['type' => 'all']) }}" id="seopress-user-consent-accept" class="button">Accept</a>
             <a href="{{ route('reject-consent', ['type' => 'all']) }}" id="seopress-user-consent-close" class="button">X</a>
         </p>
     </div>
 @endif
