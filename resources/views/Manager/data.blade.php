
@extends('Manager.layouts')
@section('main_content')
    <div class="col-md-12">
        <div class="content-panel">
            <h4><i class="fa fa-angle-right"></i> 图片新闻</h4><hr><table class="table table-striped table-advance table-hover">

                <thead>
                <tr>
                    <th><i class="fa fa-bullhorn"></i> 序号</th>
                    <th ><i class="fa fa-bookmark" ></i> 回答日期</th>
                    @foreach($questions as $keys => $val)
                    <th class="hidden-phone"><i class="fa fa-question-circle"></i> {{$val->topic}}</th>
                    @endforeach
                    <th><i class=" fa fa-edit"></i> 操作</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($answers as $key=>$value)
                    <tr >
                        <td class="hidden-phone">{{$key+1}}</td>
                        @foreach($value as $key1=>$val1)
                            @if($key1 == 'date')
                                    <td style="position:relative;">{{$val1['answer']}}</td>
                                @endif
                        @endforeach
                        @foreach($value as $key1=>$val1)
                            @if($key1 != 'date')
                                <td class="hidden-phone">
                                    @foreach($val1->answer as $key2 => $val2)
                                        {{$val2[$key2]}}
                                    @endforeach
                                </td>
                            @endif
                        @endforeach
                        <td><a href="/manager/delete/{sid}">删除</a>&nbsp;&nbsp;
                        </td>

                    </tr>
                    <tr>
                @endforeach

                </tbody>

            </table>
            {{--{{$answers->links()}}--}}
            <div class="shadowL"></div>
            <div class="shadowR"></div>
        </div><!-- /content-panel -->
    </div>
    </body>

@stop
@section('js')
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->

    <script>
        //custom select box

        $(function(){
            $('select.styled').customSelect();
        });

    </script>

@stop

