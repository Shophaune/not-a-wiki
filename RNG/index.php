<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<?php include "../scripts/header.html"; ?>
  <p><b>Note</b>: All information was provided by pseudobyte
<p><b>Random Number Generator</b>
<p>A Random Number Generator (RNG) is used in Realm Grinder to determine the results of some actions in game. The RNG it uses is pseudorandom, which means that the results of the RNG are deterministic and can be predicted in advance with sufficient information. However, the results are qualitatively random and are very hard to distinguish from real random data without careful analysis.

<p>The game uses more than one instance of the RNG, and some things in the game have a dedicated instance that only they make use of. In addition, there is a global RNG instance that everything else uses.
<p>Each RNG instance has a state, which is a single number that determines all the future values that instance will output. When something uses an RNG instance, it consumes one or more RNG values, which advances the RNG state and moves up the stream of future values. After the value is consumed, it is processed in some way to make a decision about what should happen.


<p>Aside from values being consumed, the states of instances are not reset or otherwise affected by anything in the game, such as abdication or reincarnation. However, only some instances are persistent, meaning that their state is stored in the save file and will be restored when reopening the game or importing a save.
<p>When opening the game, all RNG instances are reseeded, which gives them a new state, and then any persistent instances are overwritten with their state from the save file. When importing a save, no instances are reseeded, but persistent instances are overwritten in the same way. The states being in the save file enables a variety of forecasting tools to work.

<p><b><img src="http://musicfamily.org/realm/Factions/picks/RNGInstances.png" alt="A Thousand Coins" align="middle"></b></p>
<p>Depending on what an instance is used for, it varies what will cause a value to be consumed from the RNG and what will affect how values generated by the RNG are interpreted.
<p>For instance, the Titan spell Lightning Strike will consume a value from its RNG instance provided at least one possible target building is owned. If you own no buildings or only own Halls of Legends and have Lightning Rod (C375), no value will be consumed. How that value is interpreted to decide which building is targeted depends on the number of available targets.
<p>The properties of the game's RNG instances are summarized in the table below.

<p>The artifact RNG instance has the most complex behavior, since it is shared by all artifacts. When any number of excavations are purchased, the same process is run, once per excavation bought. First, the non-random conditions of unobtained artifacts are checked. If the non-random conditions are met, any artifacts with no random condition are found immediately.
<p>Artifacts that have a random condition and have their non-random conditions met are eligible and will consume a value from the artifact RNG for each excavation. Eligibility does not mean that an artifact can be found, and most random artifacts can be eligible with a 0% find chance. In particular, the Scarab of Fortune and Voodoo Doll have no non-random requirements aside from owning survey equipment, and are eligible even if you are in the wrong alignment to purchase the buildings their find chances are based on, and cannot obtain them in that abdication.
<p>Eligible artifacts use values from the RNG instance in their instantiation order as listed below. Note that instantiation order is slightly different from how artifacts are ordered in-game. RNG values consumed by eligible artifacts are interpreted as a probability, and the artifact will be found if the RNG value is less than that artifact's find chance. For example, an RNG value of 1% will find an artifact with a find chance of 1% or higher if that artifact consumes that value.
<p>The underlying stream of values doesn't change from anything but excavating with at least one eligible artifact, but which artifact values are tested against can change. For example, if you are eligible for one artifact and will find it in 100 excavations, then the value that will find it is 100 values out. If you become eligible for a second artifact that has a much lower find chance, how that will change the first depends on which is earlier in the instantiation order. If the new artifact is earlier, then it will move the older artifact up to excavation 50, since excavations will now consume two values per excavation and the 100th value will land on the second artifact of the 50th excavation. But if the old artifact is earlier, then the 100th value will land on the new artifact and the old artifact will not be found for at least 50 excavations, until a new value that's small enough to find it occurs in the stream.
<p>As another example, suppose again that you have one eligible artifact that will be found in 100 excavations. If you abdicate and set up a different run where you have four eligible artifacts and then excavate 24 times, that will consume 96 RNG values assuming you don't find any of them. If you then go back and become eligible for only the original artifact, it will be only 4 excavations away, or 95 earlier than before, as long as you have at least as good a find chance as before.
<p><b><img src="http://musicfamily.org/realm/Factions/picks/ArtifactOrder.png" alt="A Thousand Coins" align="middle"></b></p>

<p><b>Technical Details</b>

<p>The RNG used by Realm Grinder is the older Park-Miller minimal standard linear congruental generator (a=16807, c=0, m=231-1). Its states are integers from 1 to 231-2. When it generates a value, it multiplies by 16807 and then divides by 231-1 and takes the remainder. The resulting value is the next output of the generator and becomes the next state. Each possible state produces a different value, so all the possible values are generated in a big cycle. The state is where a particular instance is currently in that cycle.
<?php include "../scripts/footer.html"; ?>