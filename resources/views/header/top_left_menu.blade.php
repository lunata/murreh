                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ trans('navigation.home') }}</a>
                    </li>
                    
                    <li class="dropdown" id='menu2'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                           {{ trans('navigation.ques') }} <span class="caret"></span>
                        </a>
                        
                        <ul class="dropdown-menu" role="menu" id='menu2-sub'>
                            <li><a href="/ques/anketa">{{ trans('navigation.anketas') }}</a></li>
                            <li><a href="/ques/question">{{ trans('navigation.questions') }}</a></li>
                        </ul>
                    </li>
                    
                    
                    <li class="dropdown" id='menu3'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                           {{ trans('navigation.geo') }} <span class="caret"></span>
                        </a>
                        
                        <ul class="dropdown-menu" role="menu" id='menu3-sub'>
                            <li><a href="/geo/region">{{ trans('navigation.regions') }}</a></li>
                            <li><a href="/geo/district">{{ trans('navigation.districts') }}</a></li>
                            <li><a href="/geo/place">{{ trans('navigation.places') }}</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown" id='menu4'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                           {{ trans('navigation.person') }} <span class="caret"></span>
                        </a>
                        
                        <ul class="dropdown-menu" role="menu" id='menu4-sub'>
                            <li><a href="/person/informant">{{ trans('navigation.informants') }}</a></li>
                            <li><a href="/person/recorder">{{ trans('navigation.recorders') }}</a></li>
                            <li><a href="/person/occupation">{{ trans('navigation.occupations') }}</a></li>
                            <li><a href="/person/nationality">{{ trans('navigation.nationalities') }}</a></li>
                        </ul>
                    </li>
                    
                </ul>
