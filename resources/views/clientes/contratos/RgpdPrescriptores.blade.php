@if($PdfRgpd == null)
    <div class="flex space-x-1 justify-around"> 
        <svg class="h-5 w-5 stroke-current text-red-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
@else
    <div class="flex space-x-1 justify-around">
        <a href="{{$PdfRgpd}}" target="_blank" class="no-underline hover:underline text-blue-600">Link RGPD</a>
    </div>
@endif