INSERT INTO `dimension` (`name`, `describe`) VALUES ('CQ', 'CQ is a person\'s capability to function effectively in situations characterized by cultural diversity.');
INSERT INTO `dimension` (`name`, `describe`) VALUES ('EQ', 'EQ is the capacity for recognizing our own feelings and those of others, for motivating ourselves, and for managing emotions effectively in ourselves and others.\n\nAn emotional competence is a learned capability based on emotional intelligence that contributes to effective performance at work.');


INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Awareness', '1', 'It is the level of awareness and ability to plan for multicultural interactions. The extent to which you are aware of what\'s going on in a cross-cultural situation and your ability to use that awareness to manage those situations effectively.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Literacy', '1', 'It is the level of understanding about how cultures are similar and different. It refers to the extent to which you understand the role of culture in how people think and behave, and your level of familiarity with how cultures are similar and different. Cultural literacy is your understanding about culture and how it shapes behavior.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Impulse', '1', 'It is your level of interest, drive, and confidence to adapt to multicultural situations. It refers to the extent to which you\'re energized and persistent in your approach to culturally diverse situations. It includes your sense of self-confidence in your abilities as well as your sense of the rewards - both tangible and intangible - that you will gain from functioning effectively in situations characterized by cultural diversity.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Performance', '1', 'It is your level of adaptability when relating and working cross-culturally. It refers to the extent to which you can act appropriately in a culturally diverse situation. It includes your flexibility in verbal and nonverbal behaviors and your adaptability to different cultural norms.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Positive drive', '2', 'Describe Positive drive');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Empathy', '2', 'Describe Empathy');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Happy Emotions', '2', ' This includes aspects such as good mood, positive emotions, happiness, and joy. This variable is different that positive affect/optimism in the sense that, here, it refers to a person\'s natural tendency to experience positive emotions in most situations');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Emotional Self-Awareness', '2', 'This refers to the respondents\' experience of other people\'s emotions. It is related with the ability to understand and manage other people\'s emotions.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Emotional Display', '2', 'This includes aspects such as non-verbal messages that the person send and receive from others, and how the person interprets the non-verbal emotions.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Emotional Management', '2', 'This reflects respondents\' indication that they can control their emotions or fail to manage their emotions.');
INSERT INTO `component` (`name`, `dimension_id`, `describe`) VALUES ('Non-specific', '2', 'Non-specific');

INSERT INTO `dimension_scale` (`dimension_id`, `min`, `max`, `describe`) VALUES ('1', '0', '50', 'describe min0 max50 CQ');
INSERT INTO `dimension_scale` (`dimension_id`, `min`, `max`, `describe`) VALUES ('1', '50', '100', 'describe min50 max100 CQ');
INSERT INTO `dimension_scale` (`dimension_id`, `min`, `max`, `describe`) VALUES ('2', '0', '30', 'describe min0 max30 EQ');
INSERT INTO `dimension_scale` (`dimension_id`, `min`, `max`, `describe`) VALUES ('2', '30', '66', 'describe min30 max66 EQ');
INSERT INTO `dimension_scale` (`dimension_id`, `min`, `max`, `describe`) VALUES ('2', '66', '100', 'describe min66 max100 EQ');

INSERT INTO `scale` (`name`, `value`) VALUES ('Strongly disagree', '1');
INSERT INTO `scale` (`name`, `value`) VALUES ('Disagree', '2');
INSERT INTO `scale` (`name`, `value`) VALUES ('Neither agree, nor disagree', '3');
INSERT INTO `scale` (`name`, `value`) VALUES ('Agree', '4');
INSERT INTO `scale` (`name`, `value`) VALUES ('Strongly agree', '5');
INSERT INTO `scale` (`name`, `value`) VALUES ('I don\'t Know', '0');

INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I am aware of the type of specific cultural knowledge which is required to interact with people from different cultural contexts.', '1', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I automatically adapt my cultural stance and knowledge when I interact with people coming from a different culture than mine.', '1', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I can identify the type of cultural knowledge which is required in various cross-cultural contexts.', '1', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I test the validity and the accuracy of my cultural knowledge while dealing with people from different cultures.', '1', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know the legal and the economic environment of other cultures.', '2', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know foreign languages.', '2', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know other cultures religions and values.', '2', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know the artistic heritage and craft of other cultures.', '2', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know the rules of non-verbal communication in other cultures.', '2', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I like dealing with people from different cultures.', '3', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I am secure when I have to socialize with people coming from unfamiliar cultures.', '3', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I can handle the stress of adapting to new cultures.', '3', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I like living in different cultural contexts.', '3', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I am sure that I can get used to living, shopping, and eating conditions in different cultures.', '3', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I adapt my verbal communication when cross-cultural interactions require it.', '4', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('The pace of my talk is different depending on the cultural context.', '4', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I adjust my non-verbal communication when cross-cultural interactions require it.', '4', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I adapt the expression of my face according to the cultural context.', '4', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When I am faced with obstacles, I remember times I faced similar obstacles and overcame them.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I expect that I will do well on most things I try.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('Some of the major events of my life have led me to re‑evaluate what is important and not important.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I expect good things to happen.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When I am in a positive mood, solving  problems is easy for me.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When I am in a positive mood, I am able to come up with new ideas.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I motivate myself by imagining a good outcome to tasks I take on.', '5', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('Other people find it easy to confide in me.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('By looking at their facial expressions, I recognize the     emotions people are experiencing.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When another person tells me about an important event in  his or her life, I almost feel as though I experienced this event myself.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When I feel a change in emotions, I tend to come up   with new ideas.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know what other people are feeling just by looking at them.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I help other people feel better when  they are down.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I can tell how people are feeling by listening to the tone of their voice.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('It is difficult for me to understand why people feel the way  they do.', '6', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When I experience a positive emotion, I know how to  make it last.', '7', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I arrange events others enjoy.', '7', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I seek out activities that make me happy.', '7', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I use good moods to help myself keep trying in the face of obstacles.', '7', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('Emotions are one of the things that make my life worth living.', '8', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I am aware of my emotions as I experience them.', '8', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know why my emotions change.', '8', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I easily recognize my emotions as I experience them.', '8', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I find it hard to understand the non‑verbal messages of other.', '9', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I am aware of the non‑verbal messages I send to others.', '9', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I am aware of the non‑verbal messages other people send.', '9', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I know when to speak about my personal problems to others.', '10', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I have control over my emotions.', '10', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I compliment others when they have done something well.', '10', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When I am faced with a challenge, I give up because.', '10', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('When my mood changes, I see new possibilities.', '11', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I like to share my emotions with others.', '11', '2015-01-01');
INSERT INTO `question` (`text`, `component_id`, `created_date`) VALUES ('I present myself in a way that makes a good impression on others.', '11', '2015-01-01');


INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('1', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('1', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('2', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('2', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('3', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('3', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('4', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('4', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('5', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('5', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('6', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('6', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('7', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('7', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('8', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('8', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('9', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('9', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('10', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('10', '50', '100', 'describe component 50-100');

INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('11', '0', '50', 'describe component 0-50');
INSERT INTO `apilms`.`component_scale` (`component_id`, `min`, `max`, `describe`) VALUES ('11', '50', '100', 'describe component 50-100');
