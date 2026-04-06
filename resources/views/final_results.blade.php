<x-main-layout pageTitle="Country & Capitals Quiz"> 
    <div class="container">

        <div class="text-center fs-3 mb-3">
            <p class="text-info">RESULTADOS FINAIS</p>
        </div>

        <div class="row fs-3">
            <div class="col text-end">Total de questões:</div>
            <div class="col text-info">{{ $totalQuestions }}</div>
        </div>
        <div class="row fs-3">
            <div class="col text-end">Respostas Certas:</div>
            <div class="col text-success">{{ $correctAnswers }}</div>
        </div>
        <div class="row fs-3">
            <div class="col text-end">Respostas Erradas:</div>
            <div class="col text-danger">{{ $wrongAnswers }}</div>
        </div>
        <div class="row fs-1">
            <div class="col text-end">Score Final:</div>
            <div class="col {{ $porcentage < 50 ? 'text-danger' : 'text-success'}}">{{ $porcentage }}%</div>
        </div>
        <h4>Desempenho:</h4>
        <div class="progress" style="height: 30px;">
            <div class="progress-bar 
                        @if($porcentage >= 70) bg-success 
                        @elseif($porcentage >= 40) bg-warning 
                        @else bg-danger @endif"
                 role="progressbar"
                 style="width: {{ $porcentage }}%;"
                 aria-valuenow="{{ $porcentage }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
                {{ $porcentage }}%
            </div>
        </div>

        
    </div>

    <!-- cancel game -->
    <div class="text-center mt-5">
        <a href="{{ route('StartGame') }}" class="btn btn-primary mt-3 px-5">VOLTAR AO INÍCIO</a>
    </div>
</x-main-layout>