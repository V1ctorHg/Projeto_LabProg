# Projeto_LabProg

## Como rodar o projeto?

Crie uma pasta no Xampp/htdoc onde irá rodar o projeto e inicie um git bash:

```bash
  git clone https://github.com/V1ctorHg/Projeto_LabProg.git
```

Para rodar use o localhost

Realizar commits: Abre o GitHub Desktop, adicione o repostorio selecionando o caminho da pasta feita pelo git clone e abra-o.<br>
Marque as alterações feitas no arquivo que queria subir para o git e adicione um Título para o commit e envie.<br>
Na tela Principal do Desktop selecione Push Origin<br>

##Criar Branchs para não subir direto na MAIN##
Na aba superior do desktop você pode trocar a branch ou adicionar uma nova em "Current Branch"<br>
Caso seja necessário confirmar a Pull para a main, basta ir no github Web, Pull Requests, e fazer uma solicitação<br>

##

Para commitar em código, primeiramente abra o gitBash na pasta do repositório ou no cmd do VSCode

Verificar alterações no repositório

```bash
  git status
```

Adicionar Arquivos modificados 1 a 1

```bash
  git add nome_arquivo.extensão
```

Adicionar todos arquivos modificados

```bash
  git add .
```

Fazer commit

```bash
  git commit -m "Sua mensagem de commit"
```

Enviar alterações para o repositório

```bash
  git push origin nome-da-branch
```

Verificar atualizações do projeto

```bash
  git status 
  git fetch
```

Atualizar arquivos para o repositório

```bash
  git pull
```
Em caso de conflitos de códigos ou seus arquivos estejam bagunçados demais e queira codar a partir da main como está, delete sua pasta e realize o git clone novamente.

##

