@extends('layouts')

@section('content')
    <section class="wrapper">
        <h3><i class="fa fa-angle-right"></i> 问卷列表</h3>

        <div class="row mt">
            <div class="col-md-12">
                <div class="content-panel">
                    <table class="table table-striped table-advance table-hover">
                        <tr>
                            <th><i class="fa fa-bullhorn"></i> 标题</th>
                            <th class="hidden-phone"><i class="fa fa-question-circle"></i> 备注</th>
                            <th><i class="fa fa-bookmark"></i> 发起人</th>
                            <th><i class="fa fa-flag"></i> 状态</th>
                            <th><i class=" fa fa-edit"></i> 修改</th>
                            <th></th>
                        </tr>
                        <tbody>
                        @foreach($questionnaires as $questionnaire)
                            <tr>
                                <td><a>{{ $questionnaire->name }}</a></td>
                                <td class="hidden-phone">{{ $questionnaire->remark }}</td>
                                <td>{{ $questionnaire->twt_name }} </td>
                                @if($questionnaire->status == 0)
                                    <td><span class="label label-info label-mini">未发布</span></td>
                                @elseif($questionnaire->status == 1)
                                    <td><span class="label label-success label-mini">收集中</span></td>
                                @else
                                    <td><span class="label label-warning label-mini">停止收集</span></td>
                                @endif
                                <td>
                                    <a href="{{ url('questionnaire/check/' . $questionnaire->qnid) }}"><span class="badge bg-info">查看</span></a>
                                    <a href="{{ url('questionnaire/softDelete/' . $questionnaire->qnid) }}"><span class="badge bg-warning">隐藏</span></a>
                                    <a href="{{ url('questionnaire/forceDelete/' . $questionnaire->qnid . '/1') }}"><span class="badge bg-important">删除</span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        {!! $questionnaires->links() !!}
                    </table>
                </div><!-- /content-panel -->
            </div><!-- /col-md-12 -->
        </div><!-- /row -->

    </section><! --/wrapper -->
@stop