<div class="divParametres">
    <label for="formatProduit">Format du produit</label>
    <div class="parametres">
        @foreach(['Notion', 'Wicket', 'Corde', 'Colle', 'Poignée', 'Bottom Gousset', 'Reverse Lip'] as $format)
            <label>
                <input type="checkbox" class="formatProduit-checkbox" value="{{ $format }}">
                {{ $format }}
            </label><br>
        @endforeach
        <input type="hidden" name="formatProduit" id="formatProduit" value="{{ old('formatProduit') }}">
    </div>
</div>
