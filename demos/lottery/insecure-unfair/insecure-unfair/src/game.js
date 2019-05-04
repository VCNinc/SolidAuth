import React, { Component } from 'react';
import './App.css';
import { Redirect } from 'react-router-dom';

class Card extends Component {
  render() {
    var body;
    if (this.props.result == -1) {
      body = (
        <>
          <div class="card-body">
              <p class="result"></p>
          </div>
          <div>
            <input class="form-control guess" placeholder="Your Guess" />
          </div>
        </>
      );
    } else {
      body = (
        <>
          <div class="card-body">
              <p class="result">{this.props.result}</p>
          </div>
          <div>
            <input class="form-control guess" placeholder="Your Guess" />
          </div>
        </>
      );
    }

    if (this.props.color == 0) {
      return (
        <div class="card ml-2 mr-2">
          { body }  
        </div>
      );
    } else if (this.props.color == 1) {
      return (
        <div class="card ml-2 mr-2 card-win">
          { body }
        </div>
      );
    } else {
      return (
        <div class="card ml-2 mr-2 card-lose">
          { body }
        </div>
      );
    }
  }
}

class Game extends Component {
  constructor(props) {
    super(props);
    this.state = {
      balance: 100.00,
      results: ["?", "?", "?", "?", "?"],
      colors: [0, 0, 0, 0, 0],
      redirect: -1
    }
    this.playGame = this.playGame.bind(this);
  }

  redir(digit) {
    this.setState({
      redirect: digit
    });
  }

  componentWillReceiveProps() {
    this.setState({
      results: ["?", "?", "?", "?", "?"],
      colors: [0, 0, 0, 0, 0]
    });
    var guesses = document.getElementsByClassName("guess");
    for (var i = 0; i < guesses.length; i++) {
      guesses[i].value = "";
    }
  }

  playGame(e) {
    if (this.state.balance > 0) {

      var newBalance = this.state.balance;
      var win = false;
      var cards = document.getElementsByClassName("cardComponent");
      var guesses = document.getElementsByClassName("guess");
      var digit = this.props.match.params.digit;
      var randoms = [];
      console.log(cards);

      var colors = [0, 0, 0, 0, 0];
      for (var i = 0; i < digit - 1; i++) {
        randoms.push(guesses[i].value);
        colors[i] = 1;
      }

      var rand = Math.trunc(Math.random() * 10);
      while (rand == guesses[digit - 1].value) {
        rand = Math.trunc(Math.random() * 10);
      }
      randoms.push(rand);
      
      if (guesses[digit - 1].value == rand) {
        colors[digit - 1] = 1;
      } else {
        colors[digit - 1] = 2;
      }

      console.log(randoms);

      this.setState({
        colors: colors
      });

      this.setState({
        results: randoms
      });

      if (win) {
        newBalance += 0.5 * Math.pow(10, this.props.match.params.digit);;
        this.setState({
          balance: newBalance
        })
      } else {
        newBalance -= 1;
        this.setState({
          balance: newBalance
        })
      }
    } else {
      alert("Not enough money to play. Please add more money to your account.")
    } 
  }

  render() {
    var money = 0.5 * Math.pow(10, this.props.match.params.digit);
    var balance_win = this.state.balance + money;
    var balance_lose = this.state.balance - 1;
    var cards = [];
    var odds = Math.pow(10, this.props.match.params.digit);
    for (var i = 0; i < this.props.match.params.digit; i++) {
      var color = this.state.colors[i];
      if (this.state.results.length == 0) {
        var result = -1;
        cards.push(
          <Card result={result} color={ color } />
        );
      } else {
        cards.push(
          <Card result={this.state.results[i]} color={ color } />
        );
      }

    }

    if (this.state.redirect != -1) {
      var path = "/game/" + this.state.redirect;
      this.setState({
        redirect: -1
      })
      return (
        <Redirect to={path} />
      );
    }

    if (this.props.match.params.digit < 1 || this.props.match.params.digit > 5) {
      return (
        <Redirect to="/" />
      );
    }

    var allGames = [];
    var allMoney = [];
    for (var i = 1; i <= 5; i++) {
      if (i != this.props.match.params.digit) {
        allGames.push(i);
        allMoney.push(0.5 * Math.pow(10, i));
      }
    }

    return (
      <>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <div class="navbar-brand mb-0 h1">
            <a class="navbar-brand" href="#">Logo</a>
          </div>
          <div class="collapse navbar-collapse">
            <form class="form-inline my-2 my-lg-0 nav-center">
              <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Balance: ${this.state.balance}</button>
            </form>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a href="#" class="nav-link"><img src="https://media.licdn.com/dms/image/C5603AQEmSjsFrpB8KQ/profile-displayphoto-shrink_800_800/0?e=1562198400&v=beta&t=k-fOBuGYL2r8c8TzR_sjtMCzDnqnEEPpNolceCXgP-8" class="profile-img" /> Samuel Dovgin</a></li>
            </ul>
          </div>
        </nav>

        <div class="container">
          <div class="row">
            <div class="col-8">
              <h1>Win <span class="text-success">${money}.00</span></h1>
              <h3><i>Just Guess { this.props.match.params.digit } Lucky Numbers</i></h3>
              
              <div class="game text-center">
                {cards}
              </div>

              <p class="mb-0">Balance if you win: ${balance_win}</p>
              <p class="mb-0">Balance if you lose: ${balance_lose}</p>
              <p class="mb-0">Odds: 1/{odds}</p>
              <button class="btn btn-primary play-button" type="submit" onClick={this.playGame}>Play Now</button>
            </div>

            <div class="col-4">
              <div class="card card-fw card1" onClick={this.redir.bind(this, allGames[0])}>
                <div class="card-body">
                  <h5 class="card-title">Win ${ allMoney[0] }</h5>
                  <p class="card-text">Guess { allGames[0] } numbers correctly to win. Just $1 to play.</p>
                </div>
              </div>
              <div class="card card-fw card1" onClick={this.redir.bind(this, allGames[1])}>
                <div class="card-body">
                  <h5 class="card-title">Win ${ allMoney[1] }</h5>
                  <p class="card-text">Guess { allGames[1] } numbers correctly to win. Just $1 to play.</p>
                </div>
              </div>
              <div class="card card-fw card1" onClick={this.redir.bind(this, allGames[2])}>
                <div class="card-body">
                  <h5 class="card-title">Win ${ allMoney[2] }</h5>
                  <p class="card-text">Guess { allGames[2] } numbers correctly to win. Just $1 to play.</p>
                </div>
              </div>
              <div class="card card-fw card1" onClick={this.redir.bind(this, allGames[3])}>
                <div class="card-body">
                  <h5 class="card-title">Win ${ allMoney[3] }</h5>
                  <p class="card-text">Guess { allGames[3] } numbers correctly to win. Just $1 to play.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        </>
    );
  }
}

export default Game;
