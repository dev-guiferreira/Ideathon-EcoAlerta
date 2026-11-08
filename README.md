## EcoAlerta

Sistema web para registrar e acompanhar ocorrências relacionadas à arborização urbana

## Problema
- Calçadas obstruídas: raízes que danificam o pavimento e galhos caídos que bloqueiam a passagem de pedestres.
- Galhos baixos nas vias: galhos que atingem veículos de carga e oferecem riscos aos motoristas.
- Perigo na fiação: árvores em contato com a rede elétrica, causando riscos de curtos-circuitos e acidentes.
- Canais ineficientes: ouvidorias tradicionais que dificultam o envio de fotografias, a localização dos problemas e o acompanhamento das solicitações.

## Solução
- Registro de ocorrências: permite que cidadãos denunciem problemas ambientais
- Localização por GPS: identifica onde o problema está acontecendo
- Envio de fotografias: permite visualizar a situação relatada
- Painel administrativo: organiza as ocorrências por status
- Acompanhamento: permite identificar denúncias pendentes, em andamento e resolvidas

## Limitações conhecidas do EcoAlerta:
- Depende de conexão com a internet.
- A localização GPS pode apresentar imprecisões.
- Depende de serviços externos para exibir o mapa.
- As denúncias precisam de validação humana.
- Não garante que as ocorrências serão resolvidas imediatamente.
- Não possui integração direta com a prefeitura.

## Próximos passos para o EcoAlerta:
- Melhorar o design e a responsividade do site.
- Implementar notificações sobre atualizações das ocorrências.
- Aprimorar a precisão da localização GPS.
- Criar um painel administrativo mais completo.
- Implementar filtros por região e status.
- Realizar testes e corrigir possíveis erros.
- Publicar o sistema para acesso público.
- Futuramente, integrar o EcoAlerta aos órgãos responsáveis pela arborização urbana.
- adicionar cadastro para administradores e usuários.

## Funcionalidades
- Cadastro de ocorrência com fotografia, descrição e localização no mapa
- Painel geral com total de registros, pendentes, em andamento e resolvidos
- Consulta de todas as ocorrências registradas
- Tela exclusiva para ocorrências pendentes
- Menu recolhido que abre ao clicar no botão de três linhas
- Layout responsivo para celular e computador

## Tecnologias
- CSS e JavaScript
- PHP
- MySQL
- API - Leaflet e OpenStreetMap

## Como rodar
1. Instale e inicie Apache e MySQL pelo XAMPP
2. Confira usuário, senha e nome do banco em `EcoAlerta/PHP/conexao.php`
3. Coloque a pasta `EcoAlerta` dentro de `htdocs`
4. Acesse `http://localhost/EcoAlerta/EcoAlerta/PHP/painel.php`

## Integrantes:
1. Lucas Cessel Lamera - Capitão, Planejamento e Gestão Geral
2. Henrique Azevedo Sexto - Apresentador do Pitch e Slides
3. Matheus Takayuki Ohto Pestana – Documentação e Testes
4. Miguel Ferraz Barreto - Designer UI/UX e Acessibilidade
5. Kauã Miranda de Souza - Desenvolvedor e Piloto da Demo
6. Guilherme Ferreira da Silva - Desenvolvedor (Dashboard e Dados)
