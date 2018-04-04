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
                            <th><i class=" fa fa-edit"></i> 状态</th>
                            <th></th>
                        </tr>
                        <tbody>
                        @foreach($questionnaires as $questionnaire)
                            <tr>
                                <td><a>{{ $questionnaire->name }}</a></td>
                                <td class="hidden-phone">{{ $questionnaire->remark }}</td>
                                <td>{{ $questionnaire->twt_name }} </td>
                                <td><span class="label label-info label-mini">{{ $questionnaire->status }}</span></td>
                                <td>
                                    <button class="btn btn-success btn-xs" href="{{ url('admin/check/' . $questionnaire->qnid) }}"><i class="fa fa-check"></i>查看</button>
                                    <button class="btn btn-primary btn-xs" href="{{ url('admin/delete/' . $questionnaire->qnid) }}"><i class="fa fa-pencil"></i>删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $questionnaires->links() !!}
                </div><!-- /content-panel -->
            </div><!-- /col-md-12 -->
        </div><!-- /row -->

    </section><! --/wrapper -->
@show