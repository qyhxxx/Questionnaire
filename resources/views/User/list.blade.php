@extends('layouts')

@section('content')
    <section class="wrapper">
        <h3><i class="fa fa-angle-right"></i> 用户列表</h3>

        <div class="row mt">
            <div class="col-md-12">
                <div class="content-panel">
                    <table class="table table-striped table-advance table-hover">
                        <tr>
                            <th><i class="fa fa-bullhorn"></i> 天外天账号</th>
                            <th class="hidden-phone"><i class="fa fa-question-circle"></i> 学号</th>
                            <th><i class="fa fa-bookmark"></i> 真实姓名</th>
                            <th><i class="fa fa-flag"></i> 是否为超管</th>
                            <th></th>
                        </tr>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><a>{{ $user->twt_name }}</a></td>
                                <td class="hidden-phone">{{ $user->user_number }}</td>
                                <td>{{ $user->real_name }} </td>
                                @if($user->type == 1)
                                    <td><span class="label label-success label-mini">是</span></td>
                                    <td><a href="{{ url('user/toOrdMng/' . $user->twt_name) }}"><span class="badge bg-info">取消超管</span></a></td>
                                @else
                                    <td><span class="label label-warning label-mini">否</span></td>
                                    <td><a href="{{ url('user/toSupMng/' . $user->twt_name) }}"><span class="badge bg-info">设为超管</span></a></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $users->links() !!}
                </div><!-- /content-panel -->
            </div><!-- /col-md-12 -->
        </div><!-- /row -->

    </section><! --/wrapper -->
@stop