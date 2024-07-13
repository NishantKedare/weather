<?php

use function Livewire\Volt\{state, layout, rules};
use Illuminate\Support\Facades\Http;

layout('components.layouts.app');

state(['city', 'country', 'response', 'message' => 'Please Enter City and Country']);

rules(['city' => 'required', 'country' => 'required']);

$submit = function () {
    $this->validate();
    $this->response = Http::get('https://api.openweathermap.org/data/2.5/weather?q=' . strtolower($this->city) . ',' . strtolower($this->country) . '&APPID=b032e94612abb5c48fd33ca86aba044b')->json();
    if ($this->response['cod'] == 404) {
        $this->message = $this->response['message'];
    } else {
        $this->message = 'Please Enter City and Country';
    }
}

?>

<div class="bg-gray-300 rounded-lg p-12 grid grid-cols-1 gap-12">
    <div class="capitalize text-center text-xl font-bold text-gray-700 bg-white py-10 rounded-lg">weather forecast</div>
    <div class="lg:flex lg:justify-between max-lg:grid max-lg:grid-cols-1 max-lg:gap-6 w-full gap-12">
        <div class="w-full h-96 bg-white rounded-lg py-12">
            <div class="flex justify-center">
                <div class="w-4/5 grid grid-cols-1 gap-6">
                    <input wire:model="city" class="outline-none rounded-lg p-4 font-bold text-gray-800 bg-gray-300 w-full" placeholder="City">
                    @error('city')<div class="text-red-500">{{$message}}</div>@enderror
                    <input wire:model="country" class="outline-none rounded-lg p-4 font-bold text-gray-800 bg-gray-300 w-full" placeholder="Country">
                    @error('country')<div class="text-red-500">{{$message}}</div>@enderror
                    <div class="w-full flex justify-center">
                        <button wire:click="submit" class="bg-blue-500 py-3 text-white font-semibold hover:bg-blue-700 px-4 rounded-xl">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full h-96 bg-white rounded-lg  p-8">
            <div wire:loading.remove wire:target="submit" class="w-full h-full">
                @if($response && $response['cod'] == 200)
                <div class="flex justify-end">
                    <div class="rounded-full bg-gray-200">
                        <img class="w-12 h-12" src="https://openweathermap.org/img/wn/{{$response['weather'][0]['icon']}}@2x.png">
                    </div>
                </div>
                <div class="rounded-lg font-semibold text-sm lg:text-xl capitalize grid grid-cols-1 gap-4">
                    <div>City: {{$response['name']}}</div>
                    <div>Temperature: {{$response['main']['temp']}} Kelvin</div>
                    <div>Humidity: {{$response['main']['humidity']}}%</div>
                    <div>weather description: {{$response['weather'][0]['description']}}</div>
                </div>
                @else
                <div class="flex justify-center items-center h-full text-xl font-semibold text-gray-800 capitalize text-center">
                    {{$message}}
                </div>
                @endif
            </div>
            <div class="flex justify-center items-center w-full h-full hidden" wire:loading.class.remove="hidden" wire:target="submit">
                <div class="w-min">
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>