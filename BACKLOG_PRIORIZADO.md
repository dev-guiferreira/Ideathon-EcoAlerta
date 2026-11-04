# PRODUCT BACKLOG — ECOALERTA

**Projeto:** EcoAlerta  
**Evento:** Ideathon FATEC 2026  
**Metodologia de priorização:** MoSCoW

## 1. Objetivo

1. Organizar e priorizar os requisitos necessários para o desenvolvimento do EcoAlerta, definindo as funcionalidades essenciais, as melhorias complementares e os recursos que não serão desenvolvidos nesta versão.
2. O backlog foi estruturado com base na Declaração de Escopo e nas três funcionalidades núcleo estabelecidas na etapa de descoberta do projeto.

## 2. MUST HAVE — Obrigatório

Funcionalidades indispensáveis para atender ao objetivo principal do EcoAlerta.

| ID | Item do Backlog | Descrição |
|---|---|---|
| M01 | Formulário de ocorrências | Disponibilizar um formulário para que o cidadão registre problemas relacionados à arborização urbana. |
| M02 | Classificação da ocorrência | Permitir selecionar categorias como calçada obstruída, galho baixo na via e risco à fiação. |
| M03 | Envio de fotografias | Permitir anexar uma fotografia para documentar o problema identificado. |
| M04 | Captura de localização | Obter as coordenadas geográficas da ocorrência por meio do GPS do navegador. |
| M05 | Armazenamento dos registros | Salvar as informações, fotografias e coordenadas das ocorrências no banco de dados. |
| M06 | Mapa interativo | Apresentar as ocorrências georreferenciadas utilizando OpenStreetMap e Leaflet. |
| M07 | Marcadores de ocorrências | Exibir no mapa os locais das ocorrências pendentes. |
| M08 | Dashboard geral | Apresentar indicadores de ocorrências registradas, pendentes, em análise e resolvidas. |
| M09 | Listagem de ocorrências | Disponibilizar uma lista detalhada dos chamados recentes. |
| M10 | Gerenciamento de status | Permitir atualizar o andamento das ocorrências entre pendente, em análise e resolvida. |

## 3. SHOULD HAVE — Importante

Funcionalidades complementares que melhoram a organização e a utilização da plataforma, mas não fazem parte das três funcionalidades núcleo.

| ID  | Item do Backlog | Descrição |
|---  |---|---|
| S01 | Filtro por categoria | Permitir localizar ocorrências de acordo com o tipo de problema. |
| S02 | Filtro por status | Permitir consultar ocorrências de acordo com sua situação atual. |
| S03 | Visualização detalhada | Permitir consultar as informações completas de uma ocorrência selecionada. |

## 4. COULD HAVE — Desejável

Melhorias que poderão ser consideradas em futuras evoluções, caso exista disponibilidade de tempo e recursos.

| ID | Item do Backlog | Descrição |
|-----|---|---|
| C01 | Indicadores por categoria | Apresentar a quantidade de ocorrências de cada tipo de problema. |
| C02 | Filtro por período | Permitir consultar ocorrências de acordo com a data de registro. |
| C03 | Exportação de relatórios | Permitir gerar relatórios com informações das ocorrências registradas. |
| C04 | Histórico de atualizações | Manter o histórico das alterações de status das ocorrências. |

## 5. WON'T HAVE — Fora do escopo

Funcionalidades que foram explicitamente retiradas da proposta inicial e não serão desenvolvidas nesta versão.

| ID | Item do Backlog | Justificativa |
|---|---|---|
| W01 | Autenticação obrigatória e cadastro de perfis | Removidos para garantir acesso imediato à plataforma, sem barreiras de utilização. |
| W02 | Algoritmo de rotas automáticas | A plataforma será responsável pela identificação e visualização dos pontos, sem calcular trajetos para as equipes. |
| W03 | Reconhecimento automatizado de espécies por IA | A identificação será realizada manualmente pelo cidadão por meio de categorias predefinidas. |
| W04 | Integração com sistemas municipais | Não haverá comunicação direta com bancos de dados fechados dos órgãos municipais. |
| W05 | Aplicativo mobile nativo | O projeto será desenvolvido como uma aplicação web responsiva, acessível pelo navegador. |

## 6. Critérios de priorização

A classificação dos itens segue o método MoSCoW:

- **Must Have:** funcionalidades essenciais para o funcionamento e atendimento da proposta principal.
- **Should Have:** funcionalidades importantes, mas que não comprometem o objetivo central caso não sejam implementadas inicialmente.
- **Could Have:** melhorias desejáveis que poderão ser desenvolvidas futuramente.
- **Won't Have:** funcionalidades que estão explicitamente fora do escopo desta versão.

## 7. Definição do escopo

O EcoAlerta terá como foco o registro e a visualização geográfica de ocorrências relacionadas à arborização urbana, oferecendo uma plataforma web colaborativa para facilitar a identificação de problemas e apoiar a zeladoria urbana.

O desenvolvimento priorizará três funcionalidades principais: registro rápido de ocorrências, mapa interativo e painel geral de acompanhamento.

As funcionalidades classificadas como Must Have representam as entregas essenciais. Os itens Should Have e Could Have são complementos propostos para evolução do sistema, enquanto os itens Won't Have permanecem fora do escopo desta versão.

## 8. Observação

Os itens classificados como Should Have e Could Have são complementações propostas para organizar a evolução do produto. Eles não representam funcionalidades originalmente estabelecidas na Declaração de Escopo.

A priorização poderá ser revisada pela equipe conforme o andamento do desenvolvimento e as necessidades identificadas.