<?php

class ConversaController extends BaseController {

	public function criarConversa(){
		//Verifica se a request é ajax
		// if(Request::ajax()){
			$conversa = new Conversa;    		 
    		$conversa->saltConversa = rand(100, 999);
    		$conversa->dataCriacao = date('Y-m-d H:i:s');
    		$conversa->status = 1;    		
    		$checkSenha = Input::get('checkSenha');  
    		if(!empty($checkSenha)){
    			$conversa->senha = Input::get('senha');
    		}
    		else{
    			$conversa->senha = NULL;
    		}
  		
			$conversa->save();
			
			$infoConversa = DB::table('conversas')
                    ->where('dataCriacao', '=', $conversa->dataCriacao)
                    ->where('saltConversa', '=', $conversa->saltConversa)
                    ->get();

            // var_dump($infoConversa);

            echo $infoConversa[0] -> dataCriacao;
	        $link = "http://cypher.com.br/conversa/".$infoConversa[0]->idConversa.$infoConversa[0]->saltConversa;
            echo Response::json(array('erro' => '0', 'mensagemRetorno' => '', 'conteudo' => $link));

  		// }
		// else{
		// 	// Mostrar página 404 caso não seja ajax
		// 	echo "Requisição não realizada por ajax";
		// }
	}


	public function verificarParticipaConversa($tokenConversa = null){
		if(!empty($tokenConversa)){ //Verifica se TokenConversa é diferente de nulo
			$sessoes = Session::get('conversa.logadas'); //Acessa lista de sessoes
			if(!empty($sessoes)){
				$participaConversa = 0;
				foreach ($sessoes as $key => $value) { //Percorre o array de conversas para verificar se o usuário já participa da conversa
					if($value==$tokenConversa){
						$participaConversa = 1;
					}
				}
				return $participaConversa;
			}
			else{
				//Conversa não existe
				return 0;
			}
		}
		else{
			//Token vazio
			return 2;
		}
	}

	

	public function acessarConversa($tokenConversa = null){
		$conversa = new Conversa;
		$conversa->idConversa = substr($tokenConversa, 0, -3);
		$conversa->saltConversa = substr($tokenConversa, -3);
		$conversa->senha = Input::get('senha');


		$infoConversa = DB::table('conversas')
						->where('idConversa', '=', $conversa->idConversa)
						->where('saltConversa', '=', $conversa->saltConversa)
						->take(1)
						->get();


		if(!empty($infoConversa)){ //Verifica se é uma conversa válida
			$senha = $infoConversa[0]->senha; //Obtem a senha cadastrada no BD

			if(empty($senha)){//Verifica se há senha do BD é null 
				//Verifica se a pessoa já está logada na conversa
				$retorno = $this -> verificarParticipaConversa($tokenConversa);
				if($retorno == 0){ // Pessoa não logada
					Session::push('conversa.logadas', $conversa->idConversa.$conversa->saltConversa);
					echo Response::json(array('erro' => '0', 'mensagemRetorno' => '', 'conteudo' => ''));
				}
				else { //Pessoa já estava logada na conversa
					
				}
				
				
				
			}
			else{
				if($conversa->senha==$senha){//Verifica se a senha informada pelo usuário é a mesma do BD
					//Verifica se a pessoa já está logada na conversa
					$retorno = $this -> verificarParticipaConversa($tokenConversa);
					if($retorno == 0){ // Pessoa não logada
						Session::push('conversa.logadas', $conversa->idConversa.$conversa->saltConversa);
						echo Response::json(array('erro' => '0', 'mensagemRetorno' => '', 'conteudo' => ''));
					}
					else { //Pessoa já estava logada na conversa
						
					}					
				}
				else{
					//Acesso negado, pois a senha informada é diferente da senha cadastrada no BD
					echo Response::json(array('erro' => '1', 'mensagemRetorno' => 'Senha inválida', 'conteudo' => ''));
				}
			}
		}
		else{
			//Conversa não existe
			echo Response::json(array('erro' => '1', 'mensagemRetorno' => 'Conversa não existe', 'conteudo' => ''));
		}		
	}


	public function listarMensagens($tokenConversa = null){

		//Verifica se a pessoa já está logada na conversa
		$retorno = $this -> verificarParticipaConversa($tokenConversa);
		if($retorno == 1){ // Pessoa já esta participando da conversa
			$conversa = new Conversa;
			$conversa->idConversa = substr($tokenConversa, 0, -3);
			$conversa->saltConversa = substr($tokenConversa, -3);
			$ultimaMensagem = Input::get('ultimaMensagem');

			$infoConversa = DB::table('conversas')
							->where('idConversa', '=', $conversa->idConversa)
							->where('saltConversa', '=', $conversa->saltConversa)
							->take(1)
							->get();

			if(!empty($infoConversa)){
				//Retorna mensagens conversa
				if($ultimaMensagem==0){
					$infoConversa = DB::table('mensagens')
								->where('idConversa', '=', $conversa->idConversa)
								->get();
				}
				else{
					$infoConversa = DB::table('mensagens')
								->where('idConversa', '=', $conversa->idConversa)
								->take(1)
								->skip($ultimaMensagem)
								->get();
				}

				

				echo Response::json(array('erro' => '0', 'mensagemRetorno' => '', 'conteudo' => $infoConversa));
			}
			else{
				//Conversa não existe
				echo Response::json(array('erro' => '1', 'mensagemRetorno' => 'Conversa não existe', 'conteudo' => ''));
			}
		}
		else{ // Pessoa não participa da conversa
			echo Response::json(array('erro' => '1', 'mensagemRetorno' => 'Você não participa da conversa', 'conteudo' => ''));
		}

		
	}


	public function enviarMensagem($tokenConversa = null){
		$retorno = $this -> verificarParticipaConversa($tokenConversa);
		if($retorno == 1){ // Pessoa já esta participando da conversa
			$mensagem = new Mensagem;
			$mensagem->idConversa = substr($tokenConversa, 0, -3);
			$mensagem->nomeParticipante = Input::get('nomeParticipante');
			$mensagem->mensagem = Input::get('mensagem');
			$mensagem->dataInsert = date('Y-m-d H:i:s');

			$saltConversa = substr($tokenConversa, -3);

			$infoConversa = DB::table('conversas')
							->where('idConversa', '=', $mensagem->idConversa)
							->where('saltConversa', '=', $saltConversa)
							->take(1)
							->get();

			if(!empty($infoConversa)){
				//Enviar Mensagem
				$mensagem->save();
				echo Response::json(array('erro' => '0', 'mensagemRetorno' => 'Mensage enviada com sucesso!', 'conteudo' => ''));			
			}
			else{
				//Não enviar mensagem pq a conversa não existe
				echo Response::json(array('erro' => '1', 'mensagemRetorno' => 'Conversa não existe, talvez ela tenha sido excluída por exceder o limite de tempo de duração (24 horas).', 'conteudo' => ''));
			}
		}
		else{ // Pessoa não participa da conversa
			echo Response::json(array('erro' => '1', 'mensagemRetorno' => 'Você não participa da conversa', 'conteudo' => ''));			
		}
	}

}
