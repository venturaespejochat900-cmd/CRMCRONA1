<!-- <div class="flex justify-center">
    <input type="checkbox" wire:model="selected" value="{{ $value }}" class="form-checkbox mt-1 h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
</div> -->

<div class="flex justify-center">
    <input type="checkbox" wire:model="selected" value="{{ $value }}" name="{{$value}}" class="form-checkbox mt-1 h-4 w-4 text-blue-600 transition duration-150 ease-in-out cheked:bg-blue-300" onclick='lineas(this.value)'/>                                        
</div>
<script>

</script>

