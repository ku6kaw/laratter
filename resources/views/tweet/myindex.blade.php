<!-- resources/views/tweet/myindex.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('MyPage') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-10/12 md:w-8/10 lg:w-8/12">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

            <!-- プロフィール -->

            <!-- アイコン -->
            
            <!--  -->
            
            <!-- user_name -->
            <p class="text-4xl text-center w-full underline">{{ Auth::user()->name }}</p>
            <p class="text-4xl text-center w-full">{{ Auth::user()->email }}</p>
            <!--  ここまで -->
            <!--  -->

            <!-- アイコンアップロード --> 
            <!-- <form class="text-center w-full" action="{{ route('tweet.iconUp') }}" method="POST" enctype="multipart/form-data"> -->
                <!-- @csrf -->
                <!-- <div class="form-group"> -->
                    <!-- <label class="font-bold text-lg text-grey-darkest" for="image">Icon</label> -->

                    <!-- <input type="file" class="form-control-file" name='image' id="image">                     -->
                <!-- </div> -->
                <!-- <button type="submit" class="font-medium text-white bg-black shadow-lg focus:outline-none hover:bg-gray-900 hover:shadow-none"> -->
                    <!-- Upload -->
                <!-- </button> -->
            <!-- </form> -->
            

          <table class="text-center w-full border-collapse">
            <thead>
              <tr>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg text-grey-dark border-b border-grey-light">tweet</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tweets as $tweet)
              <tr class="hover:bg-grey-lighter">
                <td class="py-4 px-6 border-b border-grey-light">
                  <div class="flex">

                    

                    @if ($tweet->user_id !== Auth::id())
                      <!-- follow 状態で条件分岐 -->
                      @if(Auth::user()->followings()->where('users.id', $tweet->user->id)->exists())
                      <!-- unfollow ボタン -->
                      <form action="{{ route('unfollow', $tweet->user) }}" method="POST" class="text-left">
                        @csrf
                        <button type="submit" class="flex mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-red py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-red-500" fill="yellow" viewBox="0 0 24 24" stroke="red">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
                          </svg>
                          {{ $tweet->user->followers()->count() }}
                        </button>
                      </form>
                      @else
                      <!-- follow ボタン -->
                      <form action="{{ route('follow', $tweet->user) }}" method="POST" class="text-left">
                        @csrf
                        <button type="submit" class="flex mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-black py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
                          </svg>
                          {{ $tweet->user->followers()->count() }}
                        </button>
                      </form>
                      @endif
                    @endif
                  </div>

                  <!-- tweet -->
                  <a href="{{ route('tweet.show',$tweet->id) }}">
                    <h3 class="text-left font-bold text-lg text-grey-dark">{{$tweet->tweet}}</h3>
                  </a>
                  <!-- tweetここまで -->

                  <!-- image -->
                  <!-- <img src="{{ '/storage/' . $tweet['image']}}" class='w-100 mb-3'/> -->
                  <img src="{{ asset($tweet->image) }}" class='w-100 mb-3'/>
                  <!-- imageここまで -->


                  <div class="flex">
                    <!-- favorite 状態で条件分岐 -->
                    @if($tweet->users()->where('user_id', Auth::id())->exists())
                    <!-- unfavorite ボタン -->
                    <form action="{{ route('unfavorites',$tweet) }}" method="POST" class="text-left">
                      @csrf
                      <button type="submit" class="flex mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-red py-1 px-2 focus:outline-none focus:shadow-outline">
                        <svg class="h-6 w-6 text-red-500" fill="red" viewBox="0 0 24 24" stroke="red">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        {{ $tweet->users()->count() }}
                      </button>
                    </form>
                    @else
                    <!-- favorite ボタン -->
                    @if ($tweet->user_id !== Auth::id())
                      <form action="{{ route('favorites',$tweet) }}" method="POST" class="text-left">
                        @csrf
                        <button type="submit" class="flex mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-black py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                          </svg>
                          {{ $tweet->users()->count() }}
                        </button>
                      </form>
                      @endif
                    @endif

                    @if ($tweet->user_id === Auth::user()->id)
                    <!-- 更新ボタン -->
                    <form action="{{ route('tweet.edit',$tweet->id) }}" method="GET" class="text-left">
                      @csrf
                      <button type="submit" class="mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-white py-1 px-2 focus:outline-none focus:shadow-outline">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="black">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                    </form>
                    <!-- 削除ボタン -->
                    <form action="{{ route('tweet.destroy',$tweet->id) }}" method="POST" class="text-left">
                      @method('delete')
                      @csrf
                      <button type="submit" class="mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-white py-1 px-2 focus:outline-none focus:shadow-outline">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="black">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

