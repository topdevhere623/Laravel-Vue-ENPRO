{{-- фильтр стилизация карты яндекса --}}

<svg style="width: 0; height: 0" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <filter id="filters" x="-10%" y="-10%" width="120%" height="120%" filterUnits="objectBoundingBox" primitiveUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
        <feColorMatrix type="matrix" values="1 0 0 0 0
1 0 0 0 0
1 0 0 0 0
0 0 0 1 0" in="SourceGraphic" result="colormatrix"/>
        <feComponentTransfer in="colormatrix" result="componentTransfer">
            <feFuncR type="table" tableValues="1 0.28 0.09"/>
            <feFuncG type="table" tableValues="1 0.48 0.08"/>
            <feFuncB type="table" tableValues="1 0.74 0.2"/>
            <feFuncA type="table" tableValues="0 1"/>
        </feComponentTransfer>
        <feBlend mode="normal" in="componentTransfer" in2="SourceGraphic" result="blend"/>
    </filter>
</svg>
