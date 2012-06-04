<?php
/*
===================================
zock!

Developed by
------------
* Michael Schroeder:
   michael.p.schroeder@gmail.com 
*
*

http://zock.sf.net

zock! is a free software licensed under GPL (General public license) v3
      more information look in the root folder for "LICENSE".
===================================
*/

include_once('src/classes/interface.bet.php');

class Match implements Bet{

    /**
     * @var unixtime
     */
    protected $time;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var string
     */
    protected $matchday;

    /**
     * @var int
     */
    protected $matchday_id;

    /**
     * @var int
     */
    protected $komatch;

    /**
     * @var string
     */
    protected $home;

    /**
     * @var string
     */
    protected $visitor;

    /**
     * @var int
     */
    protected $score_h;

    /**
     * @var int
     */
    protected $score_v;

    /**
     * @var string
     */
    protected $score_special;

    /**
     * @var float
     */
    protected $jackpot;

    /**
     * @var array
     */
    protected $bets;


    /////////////////////////////////////////////////
    // CONSTRUCTOR
    /////////////////////////////////////////////////


    /**
     * @param array $dict
     * @param Event $event
     * @throws Exception
     */
    public function __construct($dict,$event) {
        if (sizeof($dict)==0)
            throw new Exception("empty match");

        $this->event = $event;
        foreach ($dict as $key => $value) {
            $this->$key = $value;
        }
    }


    /////////////////////////////////////////////////
    // GETTERS & SETTERS
    /////////////////////////////////////////////////


    /**
     * @return unixtime
     */
    function getTime()
    {
        return $this->time;
    }

    /**
     * @param $unixtime
     * @return mixed
     */
    function setTime($unixtime)
    {
        $this->time = $unixtime;
    }

    /**
     * @return array
     */
    function getDueDate()
    {
        return $this->time;
    }

    /**
     * @param array $bets
     */
    public function setBets($bets)
    {
        $this->bets = $bets;
    }

    /**
     * @return array
     */
    public function getBets()
    {
        return $this->bets;
    }

    /**
     * @param string $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }

    /**
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param float $jackpot
     */
    public function setJackpot($jackpot)
    {
        $this->jackpot = $jackpot;
    }

    /**
     * @return float
     */
    public function getJackpot()
    {
        return $this->jackpot;
    }

    /**
     * @param int $komatch
     */
    public function setKomatch($komatch)
    {
        $this->komatch = $komatch;
    }

    /**
     * @return int
     */
    public function getKomatch()
    {
        return $this->komatch;
    }

    /**
     * @param string $matchday
     */
    public function setMatchday($matchday)
    {
        $this->matchday = $matchday;
    }

    /**
     * @return string
     */
    public function getMatchday()
    {
        return $this->matchday;
    }

    /**
     * @param int $matchday_id
     */
    public function setMatchdayId($matchday_id)
    {
        $this->matchday_id = $matchday_id;
    }

    /**
     * @return int
     */
    public function getMatchdayId()
    {
        return $this->matchday_id;
    }

    /**
     * @param int $score_h
     */
    public function setScoreH($score_h)
    {
        $this->score_h = $score_h;
    }

    /**
     * @return int
     */
    public function getScoreH()
    {
        return $this->score_h;
    }

    /**
     * @param string $score_special
     */
    public function setScoreSpecial($score_special)
    {
        $this->score_special = $score_special;
    }

    /**
     * @return string
     */
    public function getScoreSpecial()
    {
        return $this->score_special;
    }

    /**
     * @param int $score_v
     */
    public function setScoreV($score_v)
    {
        $this->score_v = $score_v;
    }

    /**
     * @return int
     */
    public function getScoreV()
    {
        return $this->score_v;
    }

    /**
     * @param string $visitor
     */
    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * @return string
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @param $somebet
     * @return int
     */
    public function getSameBets($somebet)
    {
        $samebets=-1;
        $userstring = $this->event->getUsersApproved();
        $users = preg_split('/:/',$userstring);
        foreach ($users as $u) {
            if ($this->getBet($u)==$somebet) {
                $samebets++;
            }
        }
        return $samebets;
    }

    /**
     * @return string
     */
    public function getTendancy()
    {
          $toto0 = 0;
          $toto1 = 0;
          $toto2 = 0;
          $totoX = 0;
          $userstring = $this->event->getUsersApproved();
          $users = preg_split('/:/',$userstring);

          foreach ($users as $u){
              if ($this->event->getBetOn()=="results") {
                  $home = $u.'_h';
                  $visitor = $u.'_v';
                  if($this->$home == '' || $this->$visitor == ''){
                      ++$toto0;
                  }else if($this->$home > $this->$visitor){
                         ++$toto1;		}
                  else if($this->$home < $this->$visitor){
                     ++$toto2;
                  }else{
                     ++$totoX;
                  }
              }
              elseif ($this->event->getBetOn()=="toto") {
                if ($this->toto == 0) ++$toto0;
                elseif ($this->toto == 1) ++$toto1;
                elseif ($this->toto == 2) ++$toto2;
                elseif ($this->toto == 3) ++$totoX;
              }
          }
          $toto_all= count($users) - $toto0;

          if($toto1>0){$toto_trend1=round($toto1/$toto_all*100);}else{$toto_trend1=0;}
          if($totoX>0){ $toto_trendX=round($totoX/$toto_all*100);}else{$toto_trendX=0;}
          if($toto2>0){ $toto_trend2=round($toto2/$toto_all*100);}else{$toto_trend2=0;}
          return $toto_trend1.' : '.$toto_trendX.' : '.$toto_trend2;
    }

    /**
     * @param int $user
     * @return string
     */
    public function getBet($user)
    {
        $bet = "";
        if ($this->event->getBetOn()=="results") {
            $home = $user.'_h';
            $visitor = $user.'_v';
            $bet .= $this->$home;
            $bet .= ' : ';
            $bet .= $this->$visitor;
            return $bet;
        }
        elseif ($this->event->getBetOn()=="toto") {
            return $this->$user.'_h';
        }
        return "";
    }

    /**
     * @return string
     */
    public function getResult()
    {
        $result = "";
        if ($this->event->getBetOn()=="results") {
            $home = 'score_h';
            $visitor = 'score_v';
            $result .= $this->$home;
            $result .= ' : ';
            $result .= $this->$visitor;
            return $this->$result;
        }
        elseif ($this->event->getBetOn()=="toto") {
            return $this->score;
        }
        return "";    }
}