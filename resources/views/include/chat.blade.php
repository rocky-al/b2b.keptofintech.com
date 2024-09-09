<aside class="right-sidebar">
    <div class="sidebar-chat notification_list" data-plugin="chat-sidebar">
        <div class="sidebar-chat-info">
            <h6>{{ __('Notifications')}} <span class="float-right close_notification">×</span></h6>
        </div>
        <div class="chat-list">
            <div class="list-group row">
                 <a href="#" class="media">
                                <span class="d-flex">
                                    <i class="ik ik-check"></i> 
                                </span>
                                <span class="media-body">
                                    <span class="heading-font-family media-heading">{{ __('Invitation accepted')}}</span> 
                                    <span class="media-content">{{ __('Your have been Invited ...')}}</span>
                                </span>
                            </a>
                 <a href="#" class="media">
                                <span class="d-flex">
                                    <i class="ik ik-check"></i> 
                                </span>
                                <span class="media-body">
                                    <span class="heading-font-family media-heading">{{ __('Invitation accepted')}}</span> 
                                    <span class="media-content">{{ __('Your have been Invited ...')}}</span>
                                </span>
                            </a>
                 <a href="#" class="media">
                                <span class="d-flex">
                                    <i class="ik ik-check"></i> 
                                </span>
                                <span class="media-body">
                                    <span class="heading-font-family media-heading">{{ __('Invitation accepted')}}</span> 
                                    <span class="media-content">{{ __('Your have been Invited ...')}}</span>
                                </span>
                            </a>
                 <a href="#" class="media">
                                <span class="d-flex">
                                    <i class="ik ik-check"></i> 
                                </span>
                                <span class="media-body">
                                    <span class="heading-font-family media-heading">{{ __('Invitation accepted')}}</span> 
                                    <span class="media-content">{{ __('Your have been Invited ...')}}</span>
                                </span>
                            </a>
            </div>
        </div>
    </div>
</aside>

<div class="chat-panel" hidden>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <a href="javascript:void(0);"><i class="ik ik-message-square text-success"></i></a>  
            <span class="user-name">{{ __('John Doe')}}</span> 
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="card-body">
            <div class="widget-chat-activity flex-1">
                <div class="messages">
                    <div class="message media reply">
                        <figure class="user--online">
                            <a href="#">
                                <img src="{{ asset('img/users/3.jpg')}}" class="rounded-circle" alt="">
                            </a>
                        </figure>
                        <div class="message-body media-body">
                            <p>{{ __('Epic Cheeseburgers come in all kind of styles.')}}</p>
                        </div>
                    </div>
                    <div class="message media">
                        <figure class="user--online">
                            <a href="#">
                                <img src="{{ asset('img/users/1.jpg')}}" class="rounded-circle" alt="">
                            </a>
                        </figure>
                        <div class="message-body media-body">
                            <p>{{ __('Cheeseburgers make your knees weak.')}}</p>
                        </div>
                    </div>
                    <div class="message media reply">
                        <figure class="user--offline">
                            <a href="#">
                                <img src="{{ asset('img/users/5.jpg')}}" class="rounded-circle" alt="">
                            </a>
                        </figure>
                        <div class="message-body media-body">
                            <p>{{ __('Cheeseburgers will never let you down.')}}</p>
                            <p>{{ __('They will also never run around or desert you.')}}</p>
                        </div>
                    </div>
                    <div class="message media">
                        <figure class="user--online">
                            <a href="#">
                                <img src="{{ asset('img/users/1.jpg')}}" class="rounded-circle" alt="">
                            </a>
                        </figure>
                        <div class="message-body media-body">
                            <p>{{ __('A great cheeseburger is a gastronomical event.')}}</p>
                        </div>
                    </div>
                    <div class="message media reply">
                        <figure class="user--busy">
                            <a href="#">
                                <img src="{{ asset('img/users/5.jpg')}}" class="rounded-circle" alt="">
                            </a>
                        </figure>
                        <div class="message-body media-body">
                            <p>{{ __('There is a cheesy incarnation waiting for you no matter what you palete preferences are.')}}</p>
                        </div>
                    </div>
                    <div class="message media">
                        <figure class="user--online">
                            <a href="#">
                                <img src="{{ asset('img/users/1.jpg')}}" class="rounded-circle" alt="">
                            </a>
                        </figure>
                        <div class="message-body media-body">
                            <p>{{ __('If you are a vegan, we are sorry for you loss.')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="javascript:void(0)" class="card-footer" method="post">
            <div class="d-flex justify-content-end">
                <textarea class="border-0 flex-1" rows="1" placeholder="Type your message here"></textarea>
                <button class="btn btn-icon" type="submit"><i class="ik ik-arrow-right text-success"></i></button>
            </div>
        </form>
    </div>
</div>
@push('script') 
<script>
    $(document).on("click", ".close_notification", function () {
        $(".right-sidebar-toggle").trigger('click');
    });
    </script>
@endpush