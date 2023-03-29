#! /usr/bin/env python3

'''
Python script to parse out all relevant parameters from the result files created by IQ-Tree2 
after running the EvoNAPS workflow (using the modfied IQ-Tree 2 version). 

Created: August 2022
Last updated: 08.03.2022
Author: Franziska Reden
'''

import pandas as pd
import numpy as np
import re
import sys
from os import path
import gzip
from Bio import SeqIO
import math
from collections import Counter
from datetime import datetime
from parse_tree import ParseTree

def AA_models(): 

    global aa_models

    aa_models = {'POISSON': {'FREQ_A': 0.05, 'FREQ_R': 0.05, 'FREQ_N': 0.05, 'FREQ_D': 0.05, 'FREQ_C': 0.05, 'FREQ_Q': 0.05, 'FREQ_E': 0.05, 'FREQ_G': 0.05, 'FREQ_H': 0.05, 'FREQ_I': 0.05, 'FREQ_L': 0.05, 'FREQ_K': 0.05, 'FREQ_M': 0.05, 'FREQ_F': 0.05, 'FREQ_P': 0.05, 'FREQ_S': 0.05, 'FREQ_T': 0.05, 'FREQ_W': 0.05, 'FREQ_Y': 0.05, 'FREQ_V': 0.05}, \
        'Dayhoff': {'FREQ_A': 0.08712691, 'FREQ_R': 0.04090396, 'FREQ_N': 0.04043196, 'FREQ_D': 0.04687195, 'FREQ_C': 0.03347397, 'FREQ_Q': 0.03825496, 'FREQ_E': 0.04952995, 'FREQ_G': 0.08861191, 'FREQ_H': 0.03361897, 'FREQ_I': 0.03688596, 'FREQ_L': 0.08535691, 'FREQ_K': 0.08048092, 'FREQ_M': 0.01475299, 'FREQ_F': 0.03977196, 'FREQ_P': 0.05067995, 'FREQ_S': 0.06957693, 'FREQ_T': 0.05854194, 'FREQ_W': 0.01049399, 'FREQ_Y': 0.02991597, 'FREQ_V': 0.06471794}, \
            'DCMut': {'FREQ_A': 0.08712691, 'FREQ_R': 0.04090396, 'FREQ_N': 0.04043196, 'FREQ_D': 0.04687195, 'FREQ_C': 0.03347397, 'FREQ_Q': 0.03825496, 'FREQ_E': 0.04952995, 'FREQ_G': 0.08861191, 'FREQ_H': 0.03361897, 'FREQ_I': 0.03688596, 'FREQ_L': 0.08535691, 'FREQ_K': 0.08048092, 'FREQ_M': 0.01475299, 'FREQ_F': 0.03977196, 'FREQ_P': 0.05067995, 'FREQ_S': 0.06957693, 'FREQ_T': 0.05854194, 'FREQ_W': 0.01049399, 'FREQ_Y': 0.02991597, 'FREQ_V': 0.06471794}, \
                'JTT': {'FREQ_A': 0.07674792, 'FREQ_R': 0.05169095, 'FREQ_N': 0.04264496, 'FREQ_D': 0.05154395, 'FREQ_C': 0.01980298, 'FREQ_Q': 0.04075196, 'FREQ_E': 0.06182994, 'FREQ_G': 0.07315193, 'FREQ_H': 0.02294398, 'FREQ_I': 0.05376095, 'FREQ_L': 0.09190391, 'FREQ_K': 0.05867594, 'FREQ_M': 0.02382598, 'FREQ_F': 0.04012596, 'FREQ_P': 0.05090095, 'FREQ_S': 0.06876493, 'FREQ_T': 0.05856494, 'FREQ_W': 0.01426099, 'FREQ_Y': 0.03210197, 'FREQ_V': 0.06600493}, \
                    'mtREV': {'FREQ_A': 0.072, 'FREQ_R': 0.019, 'FREQ_N': 0.039, 'FREQ_D': 0.019, 'FREQ_C': 0.006, 'FREQ_Q': 0.025, 'FREQ_E': 0.024, 'FREQ_G': 0.056, 'FREQ_H': 0.028, 'FREQ_I': 0.088, 'FREQ_L': 0.169, 'FREQ_K': 0.023, 'FREQ_M': 0.054, 'FREQ_F': 0.061, 'FREQ_P': 0.054, 'FREQ_S': 0.072, 'FREQ_T': 0.086, 'FREQ_W': 0.029, 'FREQ_Y': 0.033, 'FREQ_V': 0.043}, \
                        'WAG': {'FREQ_A': 0.08662791, 'FREQ_R': 0.043972, 'FREQ_N': 0.0390894, 'FREQ_D': 0.05704511, 'FREQ_C': 0.0193078, 'FREQ_Q': 0.0367281, 'FREQ_E': 0.05805891, 'FREQ_G': 0.08325181, 'FREQ_H': 0.0244313, 'FREQ_I': 0.048466, 'FREQ_L': 0.08620901, 'FREQ_K': 0.06202861, 'FREQ_M': 0.0195027, 'FREQ_F': 0.0384319, 'FREQ_P': 0.0457631, 'FREQ_S': 0.06951791, 'FREQ_T': 0.06101271, 'FREQ_W': 0.0143859, 'FREQ_Y': 0.0352742, 'FREQ_V': 0.07089561}, \
                            'rtREV': {'FREQ_A': 0.0646, 'FREQ_R': 0.0453, 'FREQ_N': 0.0376, 'FREQ_D': 0.0422, 'FREQ_C': 0.0114, 'FREQ_Q': 0.0606, 'FREQ_E': 0.0607, 'FREQ_G': 0.0639, 'FREQ_H': 0.0273, 'FREQ_I': 0.0679, 'FREQ_L': 0.1018, 'FREQ_K': 0.0751, 'FREQ_M': 0.015, 'FREQ_F': 0.0287, 'FREQ_P': 0.0681, 'FREQ_S': 0.0488, 'FREQ_T': 0.0622, 'FREQ_W': 0.0251, 'FREQ_Y': 0.0318, 'FREQ_V': 0.0619}, \
                                'cpREV': {'FREQ_A': 0.0755, 'FREQ_R': 0.0621, 'FREQ_N': 0.041, 'FREQ_D': 0.0371, 'FREQ_C': 0.0091, 'FREQ_Q': 0.0382, 'FREQ_E': 0.0495, 'FREQ_G': 0.0838, 'FREQ_H': 0.0246, 'FREQ_I': 0.0806, 'FREQ_L': 0.1011, 'FREQ_K': 0.0504, 'FREQ_M': 0.022, 'FREQ_F': 0.0506, 'FREQ_P': 0.0431, 'FREQ_S': 0.0622, 'FREQ_T': 0.0543, 'FREQ_W': 0.0181, 'FREQ_Y': 0.0307, 'FREQ_V': 0.066}, \
                                    'VT': {'FREQ_A': 0.077076462, 'FREQ_R': 0.050081937, 'FREQ_N': 0.04623774, 'FREQ_D': 0.053792986, 'FREQ_C': 0.014453339, 'FREQ_Q': 0.040892361, 'FREQ_E': 0.063357934, 'FREQ_G': 0.065567236, 'FREQ_H': 0.021880269, 'FREQ_I': 0.05919697, 'FREQ_L': 0.097646128, 'FREQ_K': 0.059207941, 'FREQ_M': 0.022069588, 'FREQ_F': 0.041350852, 'FREQ_P': 0.04768716, 'FREQ_S': 0.070729517, 'FREQ_T': 0.056775916, 'FREQ_W': 0.01270198, 'FREQ_Y': 0.032374605, 'FREQ_V': 0.066919082}, \
                                        'Blosum62': {'FREQ_A': 0.074, 'FREQ_R': 0.052, 'FREQ_N': 0.045, 'FREQ_D': 0.054, 'FREQ_C': 0.025, 'FREQ_Q': 0.034, 'FREQ_E': 0.054, 'FREQ_G': 0.074, 'FREQ_H': 0.026, 'FREQ_I': 0.068, 'FREQ_L': 0.099, 'FREQ_K': 0.058, 'FREQ_M': 0.025, 'FREQ_F': 0.047, 'FREQ_P': 0.039, 'FREQ_S': 0.057, 'FREQ_T': 0.051, 'FREQ_W': 0.013, 'FREQ_Y': 0.032, 'FREQ_V': 0.073}, \
                                            'mtMAM': {'FREQ_A': 0.0692, 'FREQ_R': 0.0184, 'FREQ_N': 0.04, 'FREQ_D': 0.0186, 'FREQ_C': 0.0065, 'FREQ_Q': 0.0238, 'FREQ_E': 0.0236, 'FREQ_G': 0.0557, 'FREQ_H': 0.0277, 'FREQ_I': 0.0905, 'FREQ_L': 0.1675, 'FREQ_K': 0.0221, 'FREQ_M': 0.0561, 'FREQ_F': 0.0611, 'FREQ_P': 0.0536, 'FREQ_S': 0.0725, 'FREQ_T': 0.087, 'FREQ_W': 0.0293, 'FREQ_Y': 0.034, 'FREQ_V': 0.0428}, \
                                                'LG': {'FREQ_A': 0.07906592, 'FREQ_R': 0.05594094, 'FREQ_N': 0.04197696, 'FREQ_D': 0.05305195, 'FREQ_C': 0.01293699, 'FREQ_Q': 0.04076696, 'FREQ_E': 0.07158593, 'FREQ_G': 0.05733694, 'FREQ_H': 0.02235498, 'FREQ_I': 0.06215694, 'FREQ_L': 0.0990809, 'FREQ_K': 0.06459994, 'FREQ_M': 0.02295098, 'FREQ_F': 0.04230196, 'FREQ_P': 0.04403996, 'FREQ_S': 0.06119694, 'FREQ_T': 0.05328695, 'FREQ_W': 0.01206599, 'FREQ_Y': 0.03415497, 'FREQ_V': 0.06914693}, \
                                                    'mtART': {'FREQ_A': 0.054116, 'FREQ_R': 0.018227, 'FREQ_N': 0.039903, 'FREQ_D': 0.02016, 'FREQ_C': 0.009709, 'FREQ_Q': 0.018781, 'FREQ_E': 0.024289, 'FREQ_G': 0.068183, 'FREQ_H': 0.024518, 'FREQ_I': 0.092638, 'FREQ_L': 0.148658, 'FREQ_K': 0.021718, 'FREQ_M': 0.061453, 'FREQ_F': 0.088668, 'FREQ_P': 0.041826, 'FREQ_S': 0.09103, 'FREQ_T': 0.049194, 'FREQ_W': 0.029786, 'FREQ_Y': 0.039443, 'FREQ_V': 0.0577}, \
                                                        'mtZOA': {'FREQ_A': 0.06887993, 'FREQ_R': 0.02103698, 'FREQ_N': 0.03038997, 'FREQ_D': 0.02069598, 'FREQ_C': 0.00996599, 'FREQ_Q': 0.01862298, 'FREQ_E': 0.02498898, 'FREQ_G': 0.07196793, 'FREQ_H': 0.02681397, 'FREQ_I': 0.08507191, 'FREQ_L': 0.15671684, 'FREQ_K': 0.01927598, 'FREQ_M': 0.05065195, 'FREQ_F': 0.08171192, 'FREQ_P': 0.04480296, 'FREQ_S': 0.08053492, 'FREQ_T': 0.05638594, 'FREQ_W': 0.02799797, 'FREQ_Y': 0.03740396, 'FREQ_V': 0.06608293}, \
                                                            'PMB': {'FREQ_A': 0.07559244, 'FREQ_R': 0.05379462, 'FREQ_N': 0.03769623, 'FREQ_D': 0.04469553, 'FREQ_C': 0.02849715, 'FREQ_Q': 0.03389661, 'FREQ_E': 0.05349465, 'FREQ_G': 0.0779922, 'FREQ_H': 0.029997, 'FREQ_I': 0.05989401, 'FREQ_L': 0.09579042, 'FREQ_K': 0.0519948, 'FREQ_M': 0.02189781, 'FREQ_F': 0.0449955, 'FREQ_P': 0.0419958, 'FREQ_S': 0.06819318, 'FREQ_T': 0.05639436, 'FREQ_W': 0.01569843, 'FREQ_Y': 0.0359964, 'FREQ_V': 0.07149285}, \
                                                                'HIVb': {'FREQ_A': 0.060490222, 'FREQ_R': 0.066039665, 'FREQ_N': 0.044127815, 'FREQ_D': 0.042109048, 'FREQ_C': 0.020075899, 'FREQ_Q': 0.053606488, 'FREQ_E': 0.071567447, 'FREQ_G': 0.072308239, 'FREQ_H': 0.022293943, 'FREQ_I': 0.069730629, 'FREQ_L': 0.098851122, 'FREQ_K': 0.056968211, 'FREQ_M': 0.019768318, 'FREQ_F': 0.028809447, 'FREQ_P': 0.046025282, 'FREQ_S': 0.05060433, 'FREQ_T': 0.053636813, 'FREQ_W': 0.033011601, 'FREQ_Y': 0.028350243, 'FREQ_V': 0.061625237}, \
                                                                    'HIVw': {'FREQ_A': 0.0377494, 'FREQ_R': 0.057321, 'FREQ_N': 0.0891129, 'FREQ_D': 0.0342034, 'FREQ_C': 0.0240105, 'FREQ_Q': 0.0437824, 'FREQ_E': 0.0618606, 'FREQ_G': 0.0838496, 'FREQ_H': 0.0156076, 'FREQ_I': 0.0983641, 'FREQ_L': 0.0577867, 'FREQ_K': 0.0641682, 'FREQ_M': 0.0158419, 'FREQ_F': 0.0422741, 'FREQ_P': 0.0458601, 'FREQ_S': 0.0550846, 'FREQ_T': 0.0813774, 'FREQ_W': 0.019597, 'FREQ_Y': 0.0205847, 'FREQ_V': 0.0515638}, \
                                                                        'JTTDCMut': {'FREQ_A': 0.07686192, 'FREQ_R': 0.05105695, 'FREQ_N': 0.04254596, 'FREQ_D': 0.05126895, 'FREQ_C': 0.02027898, 'FREQ_Q': 0.04106096, 'FREQ_E': 0.06181994, 'FREQ_G': 0.07471393, 'FREQ_H': 0.02298298, 'FREQ_I': 0.05256895, 'FREQ_L': 0.09111091, 'FREQ_K': 0.05949794, 'FREQ_M': 0.02341398, 'FREQ_F': 0.04052996, 'FREQ_P': 0.05053195, 'FREQ_S': 0.06822493, 'FREQ_T': 0.05851794, 'FREQ_W': 0.01433599, 'FREQ_Y': 0.03230297, 'FREQ_V': 0.06637393}, \
                                                                            'FLU': {'FREQ_A': 0.04707195, 'FREQ_R': 0.05090995, 'FREQ_N': 0.07421393, 'FREQ_D': 0.04785995, 'FREQ_C': 0.02502197, 'FREQ_Q': 0.03330397, 'FREQ_E': 0.05458695, 'FREQ_G': 0.07637292, 'FREQ_H': 0.01996398, 'FREQ_I': 0.06713393, 'FREQ_L': 0.07149793, 'FREQ_K': 0.05678494, 'FREQ_M': 0.01815098, 'FREQ_F': 0.03049597, 'FREQ_P': 0.05065595, 'FREQ_S': 0.08840891, 'FREQ_T': 0.07433893, 'FREQ_W': 0.01852398, 'FREQ_Y': 0.03147397, 'FREQ_V': 0.06322894}, \
                                                                                'mtMet': {'FREQ_A': 0.043793213, 'FREQ_R': 0.012957804, 'FREQ_N': 0.057001317, 'FREQ_D': 0.016899005, 'FREQ_C': 0.011330503, 'FREQ_Q': 0.018018105, 'FREQ_E': 0.022538507, 'FREQ_G': 0.047050114, 'FREQ_H': 0.017183705, 'FREQ_I': 0.089779427, 'FREQ_L': 0.155226047, 'FREQ_K': 0.039913512, 'FREQ_M': 0.06744432, 'FREQ_F': 0.088448027, 'FREQ_P': 0.037528211, 'FREQ_S': 0.093752228, 'FREQ_T': 0.063579019, 'FREQ_W': 0.022671307, 'FREQ_Y': 0.041568212, 'FREQ_V': 0.053317416}, \
                                                                                    'mtVer': {'FREQ_A': 0.070820265, 'FREQ_R': 0.014049893, 'FREQ_N': 0.045209877, 'FREQ_D': 0.014793693, 'FREQ_C': 0.006814197, 'FREQ_Q': 0.026340887, 'FREQ_E': 0.021495189, 'FREQ_G': 0.044239978, 'FREQ_H': 0.024230988, 'FREQ_I': 0.090735055, 'FREQ_L': 0.172309914, 'FREQ_K': 0.027381186, 'FREQ_M': 0.056193972, 'FREQ_F': 0.049775775, 'FREQ_P': 0.054386273, 'FREQ_S': 0.074421863, 'FREQ_T': 0.108809946, 'FREQ_W': 0.025652687, 'FREQ_Y': 0.026484687, 'FREQ_V': 0.045853677}, \
                                                                                        'mtInv': {'FREQ_A': 0.031742313, 'FREQ_R': 0.010900704, 'FREQ_N': 0.061579225, 'FREQ_D': 0.016149206, 'FREQ_C': 0.013570105, 'FREQ_Q': 0.014644106, 'FREQ_E': 0.022311209, 'FREQ_G': 0.047847519, 'FREQ_H': 0.011641805, 'FREQ_I': 0.094322338, 'FREQ_L': 0.14940706, 'FREQ_K': 0.044438718, 'FREQ_M': 0.077262531, 'FREQ_F': 0.102287041, 'FREQ_P': 0.026290211, 'FREQ_S': 0.105939042, 'FREQ_T': 0.042869117, 'FREQ_W': 0.020701008, 'FREQ_Y': 0.046556719, 'FREQ_V': 0.059540024}, \
                                                                                            'Q.pfam': {'FREQ_A': 0.085788, 'FREQ_R': 0.057731, 'FREQ_N': 0.042028, 'FREQ_D': 0.056462, 'FREQ_C': 0.010447, 'FREQ_Q': 0.039548, 'FREQ_E': 0.067799, 'FREQ_G': 0.064861, 'FREQ_H': 0.02104, 'FREQ_I': 0.055398, 'FREQ_L': 0.100413, 'FREQ_K': 0.059401, 'FREQ_M': 0.019898, 'FREQ_F': 0.042789, 'FREQ_P': 0.039579, 'FREQ_S': 0.069262, 'FREQ_T': 0.055498, 'FREQ_W': 0.01443, 'FREQ_Y': 0.033233, 'FREQ_V': 0.064396}, \
                                                                                                'Q.pfam_gb': {'FREQ_A': 0.08766, 'FREQ_R': 0.058154, 'FREQ_N': 0.037239, 'FREQ_D': 0.048117, 'FREQ_C': 0.013233, 'FREQ_Q': 0.03808, 'FREQ_E': 0.063213, 'FREQ_G': 0.059035, 'FREQ_H': 0.021871, 'FREQ_I': 0.061155, 'FREQ_L': 0.11158, 'FREQ_K': 0.056999, 'FREQ_M': 0.022763, 'FREQ_F': 0.046732, 'FREQ_P': 0.035355, 'FREQ_S': 0.065285, 'FREQ_T': 0.052818, 'FREQ_W': 0.01555, 'FREQ_Y': 0.035618, 'FREQ_V': 0.069541}, \
                                                                                                    'Q.LG': {'FREQ_A': 0.080009, 'FREQ_R': 0.052947, 'FREQ_N': 0.041171, 'FREQ_D': 0.050146, 'FREQ_C': 0.015018, 'FREQ_Q': 0.035929, 'FREQ_E': 0.061392, 'FREQ_G': 0.064793, 'FREQ_H': 0.021709, 'FREQ_I': 0.063895, 'FREQ_L': 0.106292, 'FREQ_K': 0.057047, 'FREQ_M': 0.02344, 'FREQ_F': 0.047712, 'FREQ_P': 0.039604, 'FREQ_S': 0.06298, 'FREQ_T': 0.052863, 'FREQ_W': 0.014987, 'FREQ_Y': 0.037434, 'FREQ_V': 0.070634}, \
                                                                                                        'Q.bird': {'FREQ_A': 0.066363, 'FREQ_R': 0.054021, 'FREQ_N': 0.037784, 'FREQ_D': 0.047511, 'FREQ_C': 0.022651, 'FREQ_Q': 0.048841, 'FREQ_E': 0.071571, 'FREQ_G': 0.058368, 'FREQ_H': 0.025403, 'FREQ_I': 0.045108, 'FREQ_L': 0.100181, 'FREQ_K': 0.061361, 'FREQ_M': 0.021069, 'FREQ_F': 0.03823, 'FREQ_P': 0.053861, 'FREQ_S': 0.089298, 'FREQ_T': 0.053536, 'FREQ_W': 0.012313, 'FREQ_Y': 0.027173, 'FREQ_V': 0.065359}, \
                                                                                                            'Q.insect': {'FREQ_A': 0.063003, 'FREQ_R': 0.049585, 'FREQ_N': 0.04755, 'FREQ_D': 0.048622, 'FREQ_C': 0.015291, 'FREQ_Q': 0.044058, 'FREQ_E': 0.072012, 'FREQ_G': 0.03781, 'FREQ_H': 0.022358, 'FREQ_I': 0.066563, 'FREQ_L': 0.107325, 'FREQ_K': 0.080621, 'FREQ_M': 0.023976, 'FREQ_F': 0.041578, 'FREQ_P': 0.028532, 'FREQ_S': 0.081767, 'FREQ_T': 0.055167, 'FREQ_W': 0.009698, 'FREQ_Y': 0.032219, 'FREQ_V': 0.072265}, \
                                                                                                                'Q.mammal': {'FREQ_A': 0.067997, 'FREQ_R': 0.055503, 'FREQ_N': 0.036288, 'FREQ_D': 0.046867, 'FREQ_C': 0.021435, 'FREQ_Q': 0.050281, 'FREQ_E': 0.068935, 'FREQ_G': 0.055323, 'FREQ_H': 0.02641, 'FREQ_I': 0.041953, 'FREQ_L': 0.101191, 'FREQ_K': 0.060037, 'FREQ_M': 0.019662, 'FREQ_F': 0.036237, 'FREQ_P': 0.055146, 'FREQ_S': 0.096864, 'FREQ_T': 0.057136, 'FREQ_W': 0.011785, 'FREQ_Y': 0.02473, 'FREQ_V': 0.066223}, \
                                                                                                                    'Q.plant': {'FREQ_A': 0.074923, 'FREQ_R': 0.0505, 'FREQ_N': 0.038734, 'FREQ_D': 0.053195, 'FREQ_C': 0.0113, 'FREQ_Q': 0.037499, 'FREQ_E': 0.068513, 'FREQ_G': 0.059627, 'FREQ_H': 0.021204, 'FREQ_I': 0.058991, 'FREQ_L': 0.102504, 'FREQ_K': 0.067306, 'FREQ_M': 0.022371, 'FREQ_F': 0.043798, 'FREQ_P': 0.037039, 'FREQ_S': 0.084451, 'FREQ_T': 0.04785, 'FREQ_W': 0.012322, 'FREQ_Y': 0.030777, 'FREQ_V': 0.077097}, \
                                                                                                                        'Q.yeast': {'FREQ_A': 0.059954, 'FREQ_R': 0.042032, 'FREQ_N': 0.052518, 'FREQ_D': 0.054641, 'FREQ_C': 0.008189, 'FREQ_Q': 0.040467, 'FREQ_E': 0.070691, 'FREQ_G': 0.039935, 'FREQ_H': 0.018393, 'FREQ_I': 0.069555, 'FREQ_L': 0.109563, 'FREQ_K': 0.081967, 'FREQ_M': 0.018694, 'FREQ_F': 0.046979, 'FREQ_P': 0.031382, 'FREQ_S': 0.091102, 'FREQ_T': 0.055887, 'FREQ_W': 0.010241, 'FREQ_Y': 0.033496, 'FREQ_V': 0.064313}, \
                                                                                                                            'FLAVI': {'FREQ_A': 0.0775, 'FREQ_R': 0.053813, 'FREQ_N': 0.03395, 'FREQ_D': 0.034973, 'FREQ_C': 0.014056, 'FREQ_Q': 0.030139, 'FREQ_E': 0.054825, 'FREQ_G': 0.086284, 'FREQ_H': 0.01821, 'FREQ_I': 0.063272, 'FREQ_L': 0.103857, 'FREQ_K': 0.059646, 'FREQ_M': 0.040389, 'FREQ_F': 0.03363, 'FREQ_P': 0.036649, 'FREQ_S': 0.060915, 'FREQ_T': 0.076327, 'FREQ_W': 0.030152, 'FREQ_Y': 0.020069, 'FREQ_V': 0.071343}}

def numPara(): 

    global het_num_para
    global dna_num_para
    
    het_num_para={'uniform': 0,'+G4': 1,'+I': 1,'+I+G4': 2,'+R2': 2,'+R3': 4,'+R4': 6,'+R5': 8,'+R6': 10,'+R7': 12,'+R8': 14,'+R9': 16,'+R10': 18, '+ASC+G4': 1}
    dna_num_para={'F81+F': 3, 'GTR+F': 8, 'HKY+F': 4, 'JC': 0, 'K2P': 1, 'K3P': 2, 'K3Pu+F': 5, 'SYM': 5, 'TIM+F': 6, 'TIM2+F': 6, 'TIM2e': 3, \
              'TIM3+F': 6, 'TIM3e': 3, 'TIMe': 3, 'TN+F': 5, 'TNe': 2, 'TPM2': 2, 'TPM2u+F': 5, 'TPM3': 2, 'TPM3u+F': 5, 'TVM+F': 7, 'TVMe': 4}

def CheckFiles():
    '''Check if all relevent files with the given prefix can be found. Should one of the files be missing, the program is being exited.'''

    global initial
    global unique
    global unique_ctrl
    unique_ctrl = True 

    initial = False
    unique = False
    ctrl = 0

    if path.exists(ali_file) == False: 
        print('Could not find input file '+ali_file) 
        ctrl = 1
    else: 
        print('...file '+ali_file+' has been detected')

    for file in ['.iqtree', '.log', '.mldist', '.model.gz', '.treefile']: 

        if path.exists(prefix+file) == False: 
            print('ERROR: Could not find input file '+prefix+file+'.') 
            ctrl = 1
        else: 
            print('...file '+prefix+file+' has been detected')

    if path.exists(prefix+'-initialtree.iqtree') == True and path.exists(prefix+'-initialtree.treefile') == True: 
        print('...file '+prefix+'-initialtree.iqtree has been detected')
        print('...file '+prefix+'-initialtree.treefile has been detected')
        initial = True

    if path.exists(prefix+'.uniqueseq.phy') == True: 
        print('...file '+prefix+'.uniqueseq.phy has been detected')
        unique = True

        for file in ['-keep_ident.iqtree', '-keep_ident.log', '-keep_ident.mldist', '-keep_ident.model.gz', '-keep_ident.treefile']: 

            if path.exists(prefix+file) == False: 
                print('WARNING: Could not find file '+prefix+file+'.') 
                unique_ctrl = False
            else: 
                print('...file '+prefix+file+' has been detected')

    if ctrl != 0: 
        sys.exit(2)

def OpenFiles(): 
    '''The function opens and reads in all relevant files. The variables storing the lines of the read in files will be global variables.'''

    global initial
    global unique
    global unique_ctrl
    
    global iqtree
    global log
    global mldist
    global check

    global initial_iqtree
    global iqtree_keep
    global log_keep
    global mldist_keep
    global check_keep

    initial_iqtree = None
    iqtree_keep = None
    log_keep = None
    mldist_keep = None
    check_keep = None

    with open(prefix+'.iqtree') as t: 
        iqtree = t.readlines()

    with open(prefix+'.log') as t: 
        log = t.readlines()

    mldist = pd.read_csv(prefix+'.mldist', sep=' ', skipinitialspace = True, skiprows = 1, header = None)
    mldist.pop(mldist.columns[-1])

    with gzip.open(prefix+'.model.gz') as t: 
        check = [x.decode('utf8').strip() for x in t.readlines()] 

    if initial is True: 
        with open(prefix+'-initialtree.iqtree') as t: 
            initial_iqtree = t.readlines()

    if unique is True and unique_ctrl is True: 
        with open(prefix+'-keep_ident.iqtree') as t: 
            iqtree_keep = t.readlines()

        with open(prefix+'-keep_ident.log') as t: 
            log_keep = t.readlines()

        mldist_keep = pd.read_csv(prefix+'-keep_ident.mldist', sep=' ', skipinitialspace = True, skiprows = 1, header = None)
        mldist_keep.pop(mldist_keep.columns[-1])

        with gzip.open(prefix+'-keep_ident.model.gz') as t: 
            check_keep = [x.decode('utf8').strip() for x in t.readlines()] 

def CheckAliType(): 
    '''
    Function that checks if the underlying alignment that has been run through the workflow is a DNA or protein alignment.
    It will update the global variable "type".
    '''
    
    global type
    global initial
    type = ''

    for i in range (len(log)): 

        if log[i][:len('Command: ')] == 'Command: ': 
            if '--seqtype' in log[i]: 
                if log[i].split('--seqtype ')[1][:len('AA')] == 'AA': 
                    type = 'AA'
                elif log[i].split('--seqtype ')[1][:len('DNA')] == 'DNA':
                    type = 'DNA'

            if 'initialtree' in log[i]: 
                initial = True

            if type != '': 
                return
        
        if log[i][:len('Alignment most likely contains ')] == 'Alignment most likely contains ': 
            if 'DNA' in log[i]: 
                type = 'DNA'
            elif 'protein' in log[i]: 
                type = 'AA'

def InitialiseDataFrames(): 
    '''Function that initialises the DataFrames that will store all the relevent data.'''

    global seq_para
    global ali_para
    global model_para
    global tree_para
    global branch_para
    global file_name_dic
       
    branch_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', \
                'TREE_TYPE', 'BRANCH_INDEX', 'BRANCH_TYPE', 'BL', 'SPLIT_SIZE', \
                    '1_MIN_PATH', '1_MAX_PATH', '1_MEAN_PATH', '1_MEDIAN_PATH', \
                        '2_MIN_PATH', '2_MAX_PATH', '2_MEAN_PATH', '2_MEDIAN_PATH'])
    
    if type == 'DNA':

        seq_para = pd.DataFrame(columns = ['ALI_ID', 'SEQ_INDEX', 'SEQ_NAME', 'FRAC_WILDCARDS_GAPS', 'CHI2_P_VALUE', 'CHI2_PASSED', 'EXCLUDED', 'IDENTICAL_TO', 'FREQ_A', 'FREQ_C', 'FREQ_G', 'FREQ_T', 'SEQ'])

        ali_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'SEQ_TYPE','SEQUENCES', 'COLUMNS', 'DISTINCT_PATTERNS', 'PARSIMONY_INFORMATIVE_SITES', \
            'SINGELTON_SITES', 'CONSTANT_SITES', 'FRAC_WILDCARDS_GAPS', 'FAILED_CHI2', 'IDENTICAL_SEQ', 'EXCLUDED_SEQ'])

        model_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'KEEP_IDENT', 'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'MODEL_RATE_HETEROGENEITY', 'NUM_RATE_CAT', \
            'LOGL', 'AIC', 'WEIGHTED_AIC', 'CONFIDENCE_AIC', 'AICC', 'WEIGHTED_AICC', 'CONFIDENCE_AICC',  'BIC', 'WEIGHTED_BIC', 'CONFIDENCE_BIC', \
                'CAIC', 'WEIGHTED_CAIC', 'CONFIDENCE_CAIC', 'ABIC', 'WEIGHTED_ABIC', 'CONFIDENCE_ABIC', \
                    'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', 'TREE_LENGTH', \
                        'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_C', 'FREQ_G', 'FREQ_T', 'RATE_AC', 'RATE_AG', 'RATE_AT', 'RATE_CG', 'RATE_CT', 'RATE_GT', \
                            'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                    'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                        'PROP_CAT_9', 'REL_RATE_CAT_19', 'PROP_CAT_10', 'REL_RATE_CAT_10'])

        tree_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'TREE_TYPE', 'CHOICE_CRITERIUM', 'KEEP_IDENT', \
            'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'MODEL_RATE_HETEROGENEITY', 'NUM_RATE_CAT', \
                'LOGL', 'UNCONSTRAINED_LOGL', 'AIC', 'AICC', 'BIC', 'CAIC', 'ABIC', 'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', \
                    'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_C', 'FREQ_G', 'FREQ_T', 'RATE_AC', 'RATE_AG', 'RATE_AT', 'RATE_CG', 'RATE_CT', 'RATE_GT', \
                        'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                    'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                        'PROP_CAT_9', 'REL_RATE_CAT_19', 'PROP_CAT_10', 'REL_RATE_CAT_10', 'TREE_LENGTH', 'SUM_IBL', 'TREE_DIAMETER', 'DIST_MIN', 'DIST_MAX', \
                                        'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR', \
                                            'BL_MIN', 'BL_MAX', 'BL_MEAN', 'BL_MEDIAN', 'BL_VAR', \
                                                'IBL_MIN', 'IBL_MAX', 'IBL_MEAN', 'IBL_MEDIAN', 'IBL_VAR', \
                                                    'EBL_MIN', 'EBL_MAX', 'EBL_MEAN', 'EBL_MEDIAN', 'EBL_VAR', \
                                                        'POT_FF_7', 'POT_FF_8', 'POT_FF_9', 'POT_FF_10', 'NEWICK_STRING'])

    elif type == 'AA': 

        seq_para = pd.DataFrame(columns = ['ALI_ID', 'SEQ_INDEX', 'SEQ_NAME', 'FRAC_WILDCARDS_GAPS', 'CHI2_P_VALUE', 'CHI2_PASSED', 'EXCLUDED', 'IDENTICAL_TO', 'FREQ_A', 'FREQ_R','FREQ_N',\
            'FREQ_D','FREQ_C','FREQ_Q','FREQ_E','FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K','FREQ_M','FREQ_F',\
                'FREQ_P','FREQ_S','FREQ_T','FREQ_W', 'FREQ_Y', 'FREQ_V', 'SEQ'])

        ali_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'SEQ_TYPE', 'SEQUENCES', 'COLUMNS', 'DISTINCT_PATTERNS', 'PARSIMONY_INFORMATIVE_SITES', \
            'SINGELTON_SITES', 'CONSTANT_SITES', 'FRAC_WILDCARDS_GAPS', 'FAILED_CHI2', 'IDENTICAL_SEQ', 'EXCLUDED_SEQ'])

        model_para = pd.DataFrame(columns=['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'KEEP_IDENT', 'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'MODEL_RATE_HETEROGENEITY', 'NUM_RATE_CAT',  \
            'LOGL', 'AIC', 'WEIGHTED_AIC', 'CONFIDENCE_AIC', 'AICC', 'WEIGHTED_AICC', 'CONFIDENCE_AICC',  'BIC', 'WEIGHTED_BIC', 'CONFIDENCE_BIC', \
                'CAIC', 'WEIGHTED_CAIC', 'CONFIDENCE_CAIC', 'ABIC', 'WEIGHTED_ABIC', 'CONFIDENCE_ABIC', 'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', 'TREE_LENGTH', \
                    'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_R','FREQ_N','FREQ_D','FREQ_C','FREQ_Q','FREQ_E','FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K','FREQ_M','FREQ_F', \
                        'FREQ_P','FREQ_S','FREQ_T','FREQ_W', 'FREQ_Y', 'FREQ_V', \
                            'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                    'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                        'PROP_CAT_9', 'REL_RATE_CAT_19', 'PROP_CAT_10', 'REL_RATE_CAT_10'])

        tree_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'TREE_TYPE', 'CHOICE_CRITERIUM', 'KEEP_IDENT', \
            'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'MODEL_RATE_HETEROGENEITY', 'NUM_RATE_CAT', \
                'LOGL', 'UNCONSTRAINED_LOGL', 'AIC', 'AICC', 'BIC', 'CAIC', 'ABIC', 'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', \
                    'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_R','FREQ_N','FREQ_D','FREQ_C','FREQ_Q','FREQ_E','FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K','FREQ_M','FREQ_F', \
                        'FREQ_P','FREQ_S','FREQ_T','FREQ_W', 'FREQ_Y', 'FREQ_V', \
                            'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                    'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                        'PROP_CAT_9', 'REL_RATE_CAT_19', 'PROP_CAT_10', 'REL_RATE_CAT_10', 'TREE_LENGTH', 'SUM_IBL', 'TREE_DIAMETER', 'DIST_MIN', 'DIST_MAX', \
                                            'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR', 'BL_MIN', 'BL_MAX', 'BL_MEAN', 'BL_MEDIAN', 'BL_VAR', \
                                                'IBL_MIN', 'IBL_MAX', 'IBL_MEAN', 'IBL_MEDIAN', 'IBL_VAR', \
                                                    'EBL_MIN', 'EBL_MAX', 'EBL_MEAN', 'EBL_MEDIAN', 'EBL_VAR', \
                                                        'POT_FF_7', 'POT_FF_8', 'POT_FF_9', 'POT_FF_10', 'NEWICK_STRING'])

    file_name_dic = {'seq_para': out_prefix+'_seq_parameters.tsv', 'ali_para': out_prefix+'_ali_parameters.tsv', 'tree_para': out_prefix+'_tree_parameters.tsv', \
        'branch_para': out_prefix+'_branch_parameters.tsv', 'model_para': out_prefix+'_model_parameters.tsv'}

    seq_para.to_csv(file_name_dic['seq_para'], index = False, sep = '\t')
    ali_para.to_csv(file_name_dic['ali_para'], index = False, sep = '\t')
    tree_para.to_csv(file_name_dic['tree_para'], index = False, sep = '\t')
    branch_para.to_csv(file_name_dic['branch_para'], index = False, sep = '\t')
    model_para.to_csv(file_name_dic['model_para'], index = False, sep = '\t')

def OpenUniqueFile(file: str) -> dict: 
    '''
    Function that reads in the "phylib" file including only unique sequences created by IQ-Tree 2.
    Input: The name of the file to be read in. 
    Returns: a dictionary with the name of the sequence as key and the sequence as entry
    '''

    seq = {}
    with open (file) as t: 
        phy_file= t.readlines()
    for i in range (1,len(phy_file)): 
        phy_file[i] = phy_file[i].strip('\n')
        phy_file[i] = phy_file[i].split(' ')     
        while '' in phy_file[i]: 
            phy_file[i].remove('')
        seq.setdefault(phy_file[i][0], phy_file[i][1])

    return seq

def GetFreqPerSeq(line: str, states: list) -> dict: 
    '''
    Function that calculates the state frequencies in a given sequence.
    Input: a sequence (line), a list with the states to be calculated (with prefix "FREQ_") (states).
    Returns: A dictionary containing the frequencies.  
    '''

    count_states = Counter(line.upper())
    freqs = {}
    sum = 0
    for state in states: 
        freqs.setdefault(state, count_states[state[len('FREQ_'):]])
        sum += count_states[state[len('FREQ_'):]]
    for key in freqs.keys(): 
        freqs[key] = freqs[key]/sum

    freqs['FRAC_WILDCARDS_GAPS'] = 1-sum/len(line)

    return freqs

def CheckStateFreq(): 
    '''
    Function that calculates the state frequencies in the original alignment file or, should it exist, based on the unique.phy file created by IQ-Tree2.
    Furthermore, it reads in all sequences from the original alignment and stores it in the seq_para DataFrame.
    '''

    # Declare global variables that will be introduced in this function
    global freq_stats
    global freq_stats_unique
    global name_dic
    global name_dic_unique
    # Declare already existing global variables.
    global ali_para
    global seq_para

    states = []
    for key in seq_para.columns: 
        if key[:len('FREQ_')] == 'FREQ_' : 
            states.append(key)

    # Create dictionaries to store infromation regarding the names and index of the sequences
    name_dic = {}
    name_dic_unique = {}

    # Read in sequences with SeqIO from the Biopython library.
    if '.phy' in ali_file:
        parsed_seq = SeqIO.parse(ali_file, 'phylip')
    elif '.fasta' in ali_file or '.fa' in ali_file or '.faa' in ali_file: 
        parsed_seq = SeqIO.parse(ali_file, 'fasta')

    # For each sequence, calculate the frequencies. Update seq_para DataFrame with results. 
    # Set name_dic dctionary with the name of the sequence as key and the index as dictionary entry.
    index = 1
    for seq_record in parsed_seq:
        freqs = GetFreqPerSeq(str(seq_record.seq).upper(), states)
        freqs.update({'SEQ_INDEX': str(index), 'SEQ_NAME': str(seq_record.id).strip('\n'), 'SEQ': str(seq_record.seq)})
        seq_para = seq_para.append(freqs, ignore_index = True)
        name_dic.setdefault(str(seq_record.id).strip('\n'), str(index))
        index += 1

    # Should a unique sequence file exist, calculate state frequencies based on the sequences in the file. 
    # Otherwise, calculate state frequencies based on original alignment file.
    # The results are stored in the (global) dictionary freq_stats.

    all_seq = ''
    for x in range (len(seq_para['SEQ'])): 
        all_seq += seq_para['SEQ'][x].upper()
    freq_stats = GetFreqPerSeq(all_seq, states)

    if unique is True: 
        columns = ['SEQ']+states
        seq_para_unique = pd.DataFrame(columns=columns)
        index = 1
        phy_file = OpenUniqueFile(prefix+'.uniqueseq.phy')
        for seq in phy_file.keys():
            freqs = GetFreqPerSeq(phy_file[seq].upper(), states)
            freqs.update({'SEQ': phy_file[seq].upper()})
            name_dic_unique.setdefault(seq, str(index))
            seq_para_unique = seq_para_unique.append(freqs, ignore_index = True)
            index += 1

        all_seq = ''
        for x in range (len(seq_para_unique['SEQ'])): 
            all_seq += seq_para_unique['SEQ'][x].upper()

        freq_stats_unique = GetFreqPerSeq(all_seq, states)
    
    else: 
        name_dic_unique = name_dic
        freq_stats_unique = freq_stats        

    seq_para['EXCLUDED'] = 0

    freq_stats.pop('FRAC_WILDCARDS_GAPS', None)
    freq_stats_unique.pop('FRAC_WILDCARDS_GAPS', None)
    
def CheckIfInConfidenceInterval(char): 
    
    if char == '+': 
        return 1
    elif char == '-':
        return 0

def TransDateTime(timeStamp: str) -> datetime: 
    '''Transforms the timestamp (input as string) into datetime format.'''
    dateTime = datetime.strptime(timeStamp, '%c')
    return dateTime

def ParseAliSeqParameters(): 
    '''
    Function that gathers all information to be stored in the alignment, sequence and model parameters files from the log, iqtree and model.gz files.
    The results are written into tab seperated files (ali_parameters.tsv, model_parameters.tsv as well as seq_parameters.tsv).  
    '''    
    global ali_para
    global seq_para
    global number_columns

    # Helper dictionary that will store "constants" such as the alignment ID (ALI_ID) or timestamp.
    constant_stats = {}

    # Parse IQ-Tree2 version from first line in iqtree file.
    IQtreeVersion = iqtree[0]
    constant_stats.setdefault('IQTREE_VERSION', IQtreeVersion.split(' ')[1])

    # Read through iqtree file. Check each line and parse out relevent information. 
    for i in range (len(iqtree)): 

        if iqtree[i][:len('Input file name: ')] == 'Input file name: ': 
            constant_stats.setdefault('ALI_ID', iqtree[i][len('Input file name: '):-1].split('/')[-1])

        if iqtree[i][:len('Random seed number: ')] == 'Random seed number: ': 
            constant_stats.setdefault('RANDOM_SEED', str(iqtree[i][len('Random seed number: '):-1]))

        if iqtree[i][:len('Date and time: ')] == 'Date and time: ': 
            constant_stats.setdefault('TIME_STAMP', TransDateTime(iqtree[i][len('Date and time: '):-1]))

    # Update ali_para DataFrame
    ali_para = ali_para.append({'SEQ_TYPE': type, 'ALI_ID': constant_stats['ALI_ID'], 'RANDOM_SEED': constant_stats['RANDOM_SEED'], 'TIME_STAMP': constant_stats['TIME_STAMP'], \
        'IQTREE_VERSION': constant_stats['IQTREE_VERSION']}, ignore_index = True)

    # Read through log file. Check each line and parse out relevent information. 
    identical = 0
    excluded = 0
    for i in range (len(log)): 

        if log[i][:len('Alignment has')]=='Alignment has':
            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", log[i])
            ali_para['SEQUENCES'] = int(numbers[0])
            ali_para['COLUMNS'] = int(numbers[1])
            number_columns = int(numbers[1])
            ali_para['DISTINCT_PATTERNS'] = int(numbers[2])
            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", log[i+1])
            ali_para['PARSIMONY_INFORMATIVE_SITES'] = int(numbers[0])
            ali_para['SINGELTON_SITES'] = int(numbers[1])
            ali_para['CONSTANT_SITES'] = int(numbers[2])
        if log[i][:len('****  TOTAL')] == '****  TOTAL':
            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", log[i])
            ali_para['FAILED_CHI2'] = int(numbers[1])
            ali_para['FRAC_WILDCARDS_GAPS'] = float(float(numbers[0])/100)

            j = i+1
            seq_para.IDENTICAL_TO = seq_para.IDENTICAL_TO.astype(str)
            
            while log[j][:len('Checking for duplicate sequences: done in ')] != 'Checking for duplicate sequences: done in ':

                if 'but kept for subsequent analysis' in log[j]: 
                    seq1 = log[j].split('NOTE: ')[1].split(' ')[0]
                    seq2 = log[j].split('NOTE: ')[1].split(' is identical to ')[1].split(' ')[0]
                    indices1 = seq_para[seq_para['SEQ_NAME'] == seq1].index.values[0]
                    indices2 = seq_para[seq_para['SEQ_NAME'] == seq2].index.values[0]
                    ident_seq1 = seq_para.at[indices1, 'IDENTICAL_TO']
                    ident_seq2 = seq_para.at[indices2, 'IDENTICAL_TO']

                    if ident_seq2=='nan': 
                        seq_para.at[indices2, 'IDENTICAL_TO'] = seq1
                    else: 
                        seq_para.at[indices2, 'IDENTICAL_TO'] = seq1+','+ident_seq2
                    
                    if ident_seq1=='nan': 
                        seq_para.at[indices1, 'IDENTICAL_TO'] = seq2
                    else: 
                        seq_para.at[indices1, 'IDENTICAL_TO'] = seq2+','+ident_seq1

                    identical+=1
                j+=1
                
        if log[i][:len('Identifying sites to remove: ')]=='Identifying sites to remove: ': 
            j=i+1
            while log[j][:len('Alignment was printed to ')]!='Alignment was printed to ': 
                if 'is ignored but added at the' in log[j]: 
                    seq1 = log[j].split('NOTE: ')[1].split(' ')[0]
                    seq2 = log[j].split('(identical to ')[1].split(')')[0]
                    indices1 = seq_para[seq_para['SEQ_NAME'] == seq1].index.values[0]
                    indices2 = seq_para[seq_para['SEQ_NAME'] == seq2].index.values[0]
                    ident_seq1 = seq_para.at[indices1, 'IDENTICAL_TO']
                    ident_seq2 = seq_para.at[indices2, 'IDENTICAL_TO']
                    seq_para.at[indices1, 'EXCLUDED'] = 1

                    if ident_seq2=='nan': 
                        seq_para.at[indices2, 'IDENTICAL_TO'] = seq1
                    else: 
                        seq_para.at[indices2, 'IDENTICAL_TO'] = seq1+','+ident_seq2
                    
                    if ident_seq1=='nan': 
                        seq_para.at[indices1, 'IDENTICAL_TO'] = seq2
                    else: 
                        seq_para.at[indices1, 'IDENTICAL_TO'] = seq2+','+ident_seq1

                    identical+=1
                    excluded+=1
                j+=1

        # Update seq_para DataFrame with stats regarding each sequence.
        if log[i][:len('Analyzing sequences: done in ')] == 'Analyzing sequences: done in ': 
            j = i+1
            while log[j][:len('****  TOTAL')] != '****  TOTAL':
                seq_line = log[j].split(' ')
                while '' in seq_line: 
                    seq_line.remove('')
                indices = seq_para[seq_para['SEQ_NAME'] == seq_line[1]].index.values
                if len(indices) == 1: 
                    seq_para.at[indices[0], 'CHI2_P_VALUE'] = float(seq_line[4][:-2])
                    if seq_line[3] == 'passed':
                        seq_para.at[indices[0], 'CHI2_PASSED'] = 1
                    else: 
                        seq_para.at[indices[0], 'CHI2_PASSED'] = 0
                j+=1

    ali_para['IDENTICAL_SEQ'] = identical
    ali_para['EXCLUDED_SEQ'] = excluded
    seq_para['ALI_ID'] = constant_stats['ALI_ID']

    # Finally, write the results into the corresponding files. 
    ali_para.to_csv(file_name_dic['ali_para'], mode = 'a', sep  ='\t', index = False, header = False)
    print('...alignment parameters were written into file '+file_name_dic['ali_para'])
    seq_para.to_csv(file_name_dic['seq_para'], mode = 'a', sep  ='\t', index = False, header = False)
    print('...sequences were written into file '+file_name_dic['seq_para'])

    seq_para2 = pd.read_csv(file_name_dic['seq_para'], sep = '\t')
    seq_para2['IDENTICAL_TO'].fillna('', inplace = True)
    seq_para2.to_csv(file_name_dic['seq_para'], sep  ='\t', index = False)

def CalculateNewSelectionCriteria(no_col, logL, k): 

    CAIC = -2*logL + (math.log(no_col)+1)*k
    ABIC = -2*logL + (math.log((no_col+2)/24))*k

    return CAIC, ABIC

def ParseModelParameters(iqtree, check, model_para, mode = 'out'): 

    global freq_stats_unique
    global freq_stats
    global type
    global aa_models
    global number_columns
    global het_num_para
    global dna_num_para

    constant_stats = {}

    # Parse IQ-Tree2 version from first line in iqtree file.
    IQtreeVersion = iqtree[0]
    constant_stats.setdefault('IQTREE_VERSION', IQtreeVersion.split(' ')[1])

    # Read through iqtree file. Check each line and parse out relevent information. 
    for i in range (len(iqtree)): 

        if iqtree[i][:len('Input file name: ')] == 'Input file name: ': 
            constant_stats.setdefault('ALI_ID', iqtree[i][len('Input file name: '):-1].split('/')[-1])

        if iqtree[i][:len('Random seed number: ')] == 'Random seed number: ': 
            constant_stats.setdefault('RANDOM_SEED', str(iqtree[i][len('Random seed number: '):-1]))

        if iqtree[i][:len('Date and time: ')] == 'Date and time: ': 
            constant_stats.setdefault('TIME_STAMP', TransDateTime(iqtree[i][len('Date and time: '):-1]))

        if iqtree[i][:len('List of models sorted by ')] == 'List of models sorted by ': 

            j=i+3
            while iqtree[j][:len('AIC, ')] != 'AIC, ': 

                if iqtree[j][:len('WARNING: ')] != 'WARNING: ': 
    
                    info_for_model = iqtree[j].strip('\n')
                    info_for_model = info_for_model.split(' ')
                    while '' in info_for_model: 
                        info_for_model.remove('')

                    if len(info_for_model) == 0: 
                        break

                    model = info_for_model[0]
                    rate_het = 'uniform'

                    if type == 'DNA': 
                        freq = 'equal'
                    else: 
                        freq = 'model'

                    if '+F+' in model: 
                        rate_het = model[len(model.split('+')[0])+2:]
                        freq = 'empirical'
                    elif '+F' in model: 
                        rate_het = 'uniform'
                        freq = 'empirical'
                    elif '+' in model: 
                        rate_het = model[len(model.split('+')[0]):]

                    if '+Fo' in model or '+FO' in model: 
                        freq = 'optimized'

                    base_model=model.split('+')[0]
                    if '+F' in model: 
                        base_model=base_model+'+F'
                    
                    model_num_para=0
                    if rate_het in het_num_para.keys(): 
                        model_num_para+=het_num_para[rate_het]
                        if type=='DNA': 
                            if base_model in dna_num_para.keys(): 
                                model_num_para+=dna_num_para[base_model]
                            else: 
                                model_num_para = None
                        else: 
                            if '+F' in model: 
                                model_num_para+=19
                    else: 
                        model_num_para = None

                    number_rate = 0
                    if rate_het != 'uniform':
                        if '+G' in rate_het: 
                            number_rate = 4
                        elif '+R' in rate_het: 
                            number_rate = int(rate_het.split('+R')[-1])

                    temp_dic = {'MODEL': model, 'FREQ_TYPE': freq, 'BASE_MODEL': base_model, 'MODEL_RATE_HETEROGENEITY': rate_het, \
                        'LOGL': float(info_for_model[1]), 'AIC': float(info_for_model[2]), 'CONFIDENCE_AIC': CheckIfInConfidenceInterval(info_for_model[3]), 'WEIGHTED_AIC': float(info_for_model[4]), \
                            'AICC': float(info_for_model[5]), 'CONFIDENCE_AICC': CheckIfInConfidenceInterval(info_for_model[6]), 'WEIGHTED_AICC': float(info_for_model[7]), \
                                'BIC': float(info_for_model[8]), 'CONFIDENCE_BIC': CheckIfInConfidenceInterval(info_for_model[9]), 'WEIGHTED_BIC': float(info_for_model[10]), 'NUM_RATE_CAT': number_rate}
                    
                    if model_num_para is not None: 
                        temp_dic['NUM_MODEL_PARAMETERS'] = model_num_para

                    if freq == 'equal': 
                        temp_dic.update({'FREQ_A': 0.25, 'FREQ_C': 0.25, 'FREQ_G': 0.25, 'FREQ_T': 0.25})
                    elif freq == 'model': 
                        temp_dic.update(aa_models[model.split('+')[0]])
                    else: 
                        if mode == 'out': 
                            temp_dic.update(freq_stats_unique)    
                        elif mode == 'keep': 
                            temp_dic.update(freq_stats)       

                    model_para = model_para.append(temp_dic, ignore_index=True)           

                j+=1

    # Read through model.gz (checkpoint) file. Check each line and parse out relevent information to be stored in model_para DataFrame. 
    for i in range (len(check)):

        pot_model = check[i].split(':')
        pot_model = pot_model[0]
        indices = model_para[model_para['MODEL'] == pot_model].index.values

        freq = 'not_opt'
        if '+Fo' in pot_model or '+FO' in pot_model: 
            freq = 'optimized'

        if len(indices) == 1: 
            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", check[i][len(pot_model):])
            free_parameters = numbers[1]
            model_para.at[indices[0], 'NUM_FREE_PARAMETERS'] = free_parameters
            if pot_model == 'JC' or pot_model == 'WAG': 
                branch_number = int(numbers[1])
            model_para.at[indices[0], 'TREE_LENGTH'] = numbers[2]

            model_para.at[indices[0], 'CAIC'], model_para.at[indices[0], 'ABIC'] = CalculateNewSelectionCriteria(float(number_columns), float(model_para.at[indices[0], 'LOGL']), float(free_parameters))

            if 'Rate parameters:' in check[i]: 
                for rate in ['A-C: ', 'A-G: ', 'A-T: ', 'C-G: ', 'C-T: ', 'G-T: ']: 
                    model_para.at[indices[0], 'RATE_'+rate[0]+rate[2]] = float(check[i].split(rate)[1].split(' ')[0].strip('\n'))

            if 'Base frequencies:' in check[i] and freq == 'optimized': 
                for base in ['A: ', 'C: ', 'G: ', 'T: ']: 
                    model_para.at[indices[0], 'FREQ_'+base[0]] = float(check[i].split('Base frequencies: ')[1].split(base)[1].split(' ').strip('\n'))
            
            if 'Proportion of invariable sites: ' in check[i]: 
                model_para.at[indices[0], 'PROP_INVAR'] = float(check[i].split('Proportion of invariable sites: ')[1].split(' ')[0].strip('\n'))

            if 'Site proportion and rates: ' in check[i]: 
                rate_cat = check[i].split('Site proportion and rates: ')[1].split(')')
                if 'Gamma shape alpha:' in check[i]: 
                    model_para.at[indices[0], 'ALPHA'] = float(check[i].split('Gamma shape alpha: ')[1].split(' ')[0].strip('\n'))
                    for k in range (len(rate_cat)): 
                        numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", rate_cat[k])
                        if len(numbers) > 1:
                            model_para.at[indices[0], 'PROP_CAT_'+str(k+1)] = numbers[1]
                            model_para.at[indices[0], 'REL_RATE_CAT_'+str(k+1)] = numbers[0]
                else: 
                    for k in range (len(rate_cat)): 
                        numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", rate_cat[k])
                        if len(numbers) > 1:
                            model_para.at[indices[0], 'PROP_CAT_'+str(k+1)] = numbers[0]
                            model_para.at[indices[0], 'REL_RATE_CAT_'+str(k+1)] = numbers[1]


        if len(indices) > 1: 
            print('WARNING! Ambigious index for model '+str(pot_model))

    #Calculate the wheighted CAIC and ABIC
    min_CAIC = min(model_para['CAIC'])
    min_ABIC = min(model_para['ABIC'])
    for i in range (len(model_para['ALI_ID'])): 
        model_para.at[i, 'WEIGHTED_CAIC'] = np.exp(-0.5*(model_para['CAIC'][i]-min_CAIC))
        model_para.at[i, 'WEIGHTED_ABIC'] = np.exp(-0.5*(model_para['ABIC'][i]-min_ABIC))

    sum_w_CAIC = sum(model_para['WEIGHTED_CAIC'])
    sum_w_ABIC = sum(model_para['WEIGHTED_ABIC'])

    for i in range (len(model_para['ALI_ID'])): 
        model_para.at[i, 'WEIGHTED_CAIC'] = model_para['WEIGHTED_CAIC'][i]/sum_w_CAIC
        if model_para['WEIGHTED_CAIC'][i] > 0.05: 
            model_para.at[i, 'CONFIDENCE_CAIC'] = 1
        else: 
            model_para.at[i, 'CONFIDENCE_CAIC'] = 0
        model_para.at[i, 'WEIGHTED_ABIC'] = model_para['WEIGHTED_ABIC'][i]/sum_w_ABIC
        if model_para['WEIGHTED_ABIC'][i] > 0.05: 
            model_para.at[i, 'CONFIDENCE_ABIC'] = 1
        else: 
            model_para.at[i, 'CONFIDENCE_ABIC'] = 0

    # Update DataFrames with "constants" (such as ALI_ID).
    model_para['ALI_ID'] = constant_stats['ALI_ID']
    model_para['RANDOM_SEED'] = constant_stats['RANDOM_SEED']
    model_para['TIME_STAMP'] = constant_stats['TIME_STAMP']
    model_para['IQTREE_VERSION'] = constant_stats['IQTREE_VERSION']
    if mode == 'out': 
        model_para['KEEP_IDENT'] = 0
    elif mode == 'keep': 
        model_para['KEEP_IDENT'] = 1
    model_para['NUM_BRANCHES'] = branch_number

    model_para.to_csv(file_name_dic['model_para'], mode = 'a', sep = '\t', index = False, header = False)
    print('...model parameters were written into file '+file_name_dic['model_para'])

    return constant_stats, branch_number

def ParseTreeParameters(tree_type, file, mldist, branch_number, mode = 'out'): 
    '''
    Function that gathers information to be stored in the tree parameters file from the iqtree file.
    
    Input 
    --------
    mode : str
        Declares if the iqtree file to be parsed containes information regarding the ml or initial tree. 
        Can be "in" or "ml". 
    file : list 
        List of lines from the read in iqtree file.

    Returns
    --------
    dict
        Dictionary containing information to be stored in the tree parameters file.
    bool
        Returns True if tree is rooted and False if tree is unrooted. 
    ''' 
    global type
    global number_columns

    root = False
    tree_stats = {}

    IQtreeVersion = file[0]
    tree_stats['IQTREE_VERSION'] = IQtreeVersion.split(' ')[1]
    tree_stats['TREE_TYPE'] = tree_type
    tree_stats['NUM_BRANCHES'] = branch_number
    
    for i in range (len(file)): 

        if file[i][:len('Input file name: ')] == 'Input file name: ': 
            tree_stats['ALI_ID'] = file[i][len('Input file name: '):-1].split('/')[-1]

        if file[i][:len('Random seed number: ')] == 'Random seed number: ': 
            tree_stats['RANDOM_SEED'] = str(file[i][len('Random seed number: '):-1])

        if file[i][:len('Date and time: ')] == 'Date and time: ': 
            tree_stats['TIME_STAMP'] = TransDateTime(file[i][len('Date and time: '):-1])

        if file[i][:len('Best-fit model according to ')] == 'Best-fit model according to ': 
            tree_stats['CHOICE_CRITERIUM'] = file[i].split('according to ')[1].split(':')[0]

        if file[i][:len('Model of substitution: ')] == 'Model of substitution: ': 
                        
            best_model = file[i][len('Model of substitution: '):-1]

            rate_het = 'uniform'
            if '+F+' in best_model: 
                rate_het = best_model[len(best_model.split('+')[0])+2:]
            elif '+F' in best_model: 
                rate_het = 'uniform'
            elif '+' in best_model: 
                rate_het = best_model[len(best_model.split('+')[0]):]

            if type == 'DNA': 
                freq = 'equal'
            elif type == 'AA': 
                freq = 'model'

            if '+FO' in best_model or '+Fo' in best_model: 
                freq = 'optimized'
            elif '+F' in best_model: 
                freq = 'empirical'
                if mode == 'keep': 
                    tree_stats.update(freq_stats)
                elif mode == 'out': 
                    tree_stats.update(freq_stats_unique)
            
            if freq == 'equal': 
                tree_stats.update({'FREQ_A':0.25, 'FREQ_C':0.25, 'FREQ_G':0.25, 'FREQ_T':0.25})
            elif freq == 'model': 
                tree_stats.update(aa_models[best_model.split('+')[0]])

            tree_stats['FREQ_TYPE'] = freq
            if mode == 'out': 
                tree_stats['KEEP_IDENT'] = 0
            elif mode == 'keep': 
                tree_stats['KEEP_IDENT'] = 1

            number_rate = 0
            if rate_het != 'uniform':
                if '+G' in rate_het: 
                    number_rate = 4
                elif '+R' in rate_het: 
                    number_rate = int(rate_het.split('+R')[-1])

            tree_stats['MODEL'] = best_model
            tree_stats['MODEL_RATE_HETEROGENEITY'] = rate_het
            tree_stats['NUM_RATE_CAT'] = number_rate

            base_model=best_model.split('+')[0]
            if '+F' in best_model: 
                base_model=base_model+'+F'

            model_num_para=0
            if rate_het in het_num_para.keys(): 
                model_num_para+=het_num_para[rate_het]
                if type=='DNA': 
                    if base_model in dna_num_para.keys(): 
                        model_num_para+=dna_num_para[base_model]
                    else: 
                        model_num_para = None
                else: 
                    if '+F' in base_model: 
                        model_num_para+=19
            else: 
                model_num_para = None

            tree_stats['BASE_MODEL'] = base_model

            if model_num_para is not None: 
                tree_stats['NUM_MODEL_PARAMETERS'] = model_num_para

        if file[i][:len('Rate parameter R:')] == 'Rate parameter R:': 

            j = i+2
            while '-' in file[j]: 
                rate = [file[j].split('-')[0][-1], file[j].split('-')[1][0]] 
                tree_stats['RATE_'+rate[0]+rate[1]] = float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[j])[0])
                j += 1   

        if file[i][:len('State frequencies: ')] == 'State frequencies: ' and freq == 'optimized': 
            j = i+2
            while 'pi(' in file[j]: 
                tree_stats['FREQ_'+file[j].split('pi(')[1].split(')')[0]] = float(file[j].split(' = ')[1].strip('\n'))

        if file[i][:len('Gamma shape alpha: ')] == 'Gamma shape alpha: ': 
            tree_stats['ALPHA']  = float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i])[0])

        if file[i][:len(' Category  Relat')] == ' Category  Relat':

            for j in range (1, 11, 1):

                if file[i+j][:len('  ')] == '  ': 
                    numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i+j])
                    if int(numbers[0]) == 0: 
                        tree_stats['PROP_INVAR'] = float(numbers[2])
                    else: 
                        tree_stats['REL_RATE_CAT_'+str(numbers[0])+''] = float(numbers[1])
                        tree_stats['PROP_CAT_'+str(numbers[0])+''] =  float(numbers[2])
                else: 
                    break

        if file[i][:len('Total tree length (sum of branch lengths): ')] == 'Total tree length (sum of branch lengths): ': 
            tree_stats['TREE_LENGTH'] = float(file[i][len('Total tree length (sum of branch lengths): '):-1])
        
        if file[i][:len('Log-likelihood of ')]=='Log-likelihood of ':
            tree_stats['LOGL'] =  float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i])[0])
            tree_stats['UNCONSTRAINED_LOGL'] =  float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i+1])[0])
            tree_stats['NUM_FREE_PARAMETERS'] = int(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i+2])[0])
            tree_stats['AIC'] =  float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i+3])[0])
            tree_stats['AICC'] =  float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i+4])[0])
            tree_stats['BIC'] =  float(re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", file[i+5])[0]) 
            tree_stats['CAIC'], tree_stats['ABIC'] = CalculateNewSelectionCriteria(float(number_columns), float(tree_stats['LOGL']), float(tree_stats['NUM_FREE_PARAMETERS']))

        if file[i][:len('Tree in newick format:')] == 'Tree in newick format:': 

            newick_string = file[i+2].strip('\n')

            if newick_string[-2:] == ');': 
                root =  False
            else: 
                root = True

            tree_stats['NEWICK_STRING'] = newick_string

        if file[i][:len('Sum of internal branch lengths: ')] == 'Sum of internal branch lengths: ': 
            tree_stats['SUM_IBL'] = float(file[i].split(': ')[1].split(' ')[0].strip('\n'))

    if type == 'initial': 
        return tree_stats, root
         
    else: 
        tree_stats['DIST_MAX'] = 0
        tree_stats['DIST_MIN'] = float('+inf')
        all_distances=[]

        for i in range (1, len(mldist.columns)): 
            for j in range (i, len(mldist)):
                all_distances.append(mldist[i][j])
                if mldist[i][j] > tree_stats['DIST_MAX']: 
                    tree_stats['DIST_MAX'] = mldist[i][j]
                if mldist[i][j] < tree_stats['DIST_MIN']: 
                    tree_stats['DIST_MIN'] = mldist[i][j]

        tree_stats['DIST_MEAN'] = np.mean(all_distances)
        tree_stats['DIST_MEDIAN'] = np.median(all_distances)
        tree_stats['DIST_VAR'] = np.var(all_distances)

        return tree_stats, root

def CalculateSelectionCriterium(no_col, logL, k): 
    '''
    Function that calculates the selection criteria (BIC, AIC, AICC) for given input. 
    Input: Number of columns (no_col), log Likelihood (logL), number of parameters of model (no_model_para) 
    as well as the global variabel branch_number depicting the number of branches in the tree.
    Returns: AIC, AICC, BIC
    '''
    
    AIC = -2*logL + 2*k
    if (no_col - k - 1) == 0: 
        AICc = AIC + 2*k*(k + 1)
    else: 
        AICc = AIC + 2*k*((k + 1) / (no_col - k - 1))
    BIC = -2*logL + k*math.log(no_col)

    return AIC, AICc, BIC

def ParseInitialTree(log, constant_stats, branch_number, name_dic_unique, mode = 'out'): 
    '''
    Function that parses through log file to gather all relevent information regarding the initial tree. 
    Requires: variables log file, name_dic_unique, branch_number, constant_stats

    Returns: 
    ---------
    tree_stats : dict
        A dictionary containing information to be stored in the tree parameters file. 
    root : bool
        Is True is tree is rooted and False if tree is unrooted.
    Treefile_name : str
        The name of the treefile the Newick sting of the initial tree was written into. 
    '''

    global het_num_para
    global dna_num_para 

    tree_stats = {}
    no_col = 0
    root = False

    tree_stats['IQTREE_VERSION'] = constant_stats['IQTREE_VERSION']
    tree_stats['TREE_TYPE'] = 'initial'
    tree_stats['ALI_ID'] = constant_stats['ALI_ID']
    tree_stats['RANDOM_SEED'] = constant_stats['RANDOM_SEED']
    tree_stats['TIME_STAMP'] = constant_stats['TIME_STAMP']

    for i in range (len(log)):

        if log[i][:len('Alignment has')] == 'Alignment has':
            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", log[i])
            no_col = int(numbers[1])

        if log[i][:len('Perform fast likelihood tree search using ')] == 'Perform fast likelihood tree search using ':
            
            start_model = log[i].split('Perform fast likelihood tree search using ')[1].split(' ')[0]

            if 'GTR' in start_model and 'GTR+F' not in start_model: 
                start_model = start_model.replace('GTR', 'GTR+F')

            if '+G' in start_model and '+G4' not in start_model: 
                start_model = start_model.replace('+G', '+G4')

            if type == 'DNA': 
                freq = 'equal'
            elif type == 'AA': 
                freq = 'model'

            if '+FO' in start_model or '+Fo' in start_model: 
                freq = 'optimized'
            elif '+F' in start_model: 
                freq = 'empirical'
                if mode == 'keep': 
                    tree_stats.update(freq_stats)
                elif mode == 'out': 
                    tree_stats.update(freq_stats_unique)
            
            if freq == 'euqal': 
                tree_stats.update({'FREQ_A':0.25, 'FREQ_C':0.25, 'FREQ_G':0.25, 'FREQ_T':0.25})
            elif freq == 'model': 
                tree_stats.update(aa_models[start_model.split('+')[0]])

            tree_stats['FREQ_TYPE'] = freq
            if mode == 'out': 
                tree_stats['KEEP_IDENT'] = 0
            elif mode == 'keep': 
                tree_stats['KEEP_IDENT'] = 1

            rate_het = 'uniform'
            if '+F+' in start_model: 
                rate_het = start_model[len(start_model.split('+')[0])+2:]
            elif '+F' in start_model: 
                rate_het = 'uniform'
            elif '+' in start_model: 
                rate_het = start_model[len(start_model.split('+')[0]):]

            number_rate = 0
            if rate_het != 'uniform':
                if '+G' in rate_het: 
                    number_rate = 4
                elif '+R' in rate_het: 
                    number_rate = int(rate_het.split('+R')[-1])

            tree_stats['MODEL'] = start_model           
            tree_stats['MODEL_RATE_HETEROGENEITY'] = rate_het
            tree_stats['NUM_RATE_CAT'] = number_rate

            base_model=start_model.split('+')[0]
            if '+F' in start_model: 
                base_model=base_model+'+F'

            model_num_para=0
            if rate_het in het_num_para.keys(): 
                model_num_para+=het_num_para[rate_het]
                if type=='DNA': 
                    if base_model in dna_num_para.keys(): 
                        model_num_para+=dna_num_para[base_model]
                    else: 
                        model_num_para = None
                else: 
                    if '+F' in base_model: 
                        model_num_para+=19
            else: 
                model_num_para = None

            tree_stats['BASE_MODEL'] = base_model
            if model_num_para is not None:
                tree_stats['NUM_MODEL_PARAMETERS'] = model_num_para

            j = i+1
            gamma=False
            while log[j][:len('ModelFinder will test up to')] !=  'ModelFinder will test up to': 

                if log[j][:len('Optimal log-likelihood: ')] == 'Optimal log-likelihood: ': 
                    tree_stats['LOGL'] = float(log[j].split('Optimal log-likelihood: ')[1].split(' ')[0])

                if log[j][:len('Rate parameters:  ')] == 'Rate parameters:  ': 
                    for rate in ['A-C: ', 'A-G: ', 'A-T: ', 'C-G: ', 'C-T: ', 'G-T: ']: 
                        tree_stats['RATE_'+rate[0]+rate[2]] = float(log[j].split(rate)[1].split(' ')[0].strip('\n'))

                if log[j][:len('Proportion of invariable sites: ')] == 'Proportion of invariable sites: ': 
                    tree_stats['PROP_INVAR'] = float(log[j].split('Proportion of invariable sites: ')[1].split(' ')[0].strip('\n'))

                if log[j][:len('Gamma shape alpha: ')] == 'Gamma shape alpha: ': 
                    tree_stats['ALPHA'] = float(log[j].split('Gamma shape alpha: ')[1].split(' ')[0].strip('\n'))
                    gamma=True

                if log[j][:len('Site proportion and rates: ')] == 'Site proportion and rates: ':
                    rate_cat = log[j].split('Site proportion and rates: ')[1].split(')')
                    if gamma is True: 
                        for k in range (len(rate_cat)): 
                            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", rate_cat[k])
                            if len(numbers) > 1:
                                tree_stats['REL_RATE_CAT_'+str(k+1)] = numbers[0]
                                tree_stats['PROP_CAT_'+str(k+1)] = numbers[1]         
                    else:               
                        for k in range (len(rate_cat)): 
                            numbers = re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", rate_cat[k])
                            if len(numbers) > 1:
                                tree_stats['REL_RATE_CAT_'+str(k+1)] = numbers[1]
                                tree_stats['PROP_CAT_'+str(k+1)] = numbers[0]
                j+=1
            
            if np.isnan(tree_stats['LOGL']) is True: 
                print('Warning! Could not find logL of the initial tree in the log file produced by IQTree!\n \
                      Please delete all output files generated by IQ-Tree2 and restart the Snakemake worflow.')
                            
            tree_stats['AIC'], tree_stats['AICC'], tree_stats['BIC'] = CalculateSelectionCriterium(no_col, tree_stats['LOGL'], model_num_para+branch_number)
            tree_stats['NUM_FREE_PARAMETERS'] = branch_number + model_num_para
            tree_stats['NUM_BRANCHES'] = branch_number
            tree_stats['CAIC'], tree_stats['ABIC'] = CalculateNewSelectionCriteria(float(number_columns), float(tree_stats['LOGL']), float(tree_stats['NUM_FREE_PARAMETERS']))

        if log[i][:len('initTree: ')] == 'initTree: ': 

            newick_string = log[i].split('initTree: ')[1].strip('\n')

            for key in name_dic_unique.keys(): 
                newick_string = newick_string.replace(','+str(int(name_dic_unique[key])-1)+':', ','+key+':')
                newick_string = newick_string.replace('('+str(int(name_dic_unique[key])-1)+':', '('+key+':')

            if mode == 'out': 
                with open (out_prefix+'-parsed_initialtree.treefile', 'w') as w: 
                    w.write(newick_string)
                file_name = out_prefix+'-parsed_initialtree.treefile'
            elif mode == 'keep': 
                with open (out_prefix+'-keep_ident_parsed_initialtree.treefile', 'w') as w: 
                    w.write(newick_string)
                file_name = out_prefix+'-keep_ident_parsed_initialtree.treefile'
            if newick_string[-2:] == ');': 
                root =  False
            else: 
                root = True

            tree_stats['NEWICK_STRING'] = newick_string
    
    if tree_stats['NEWICK_STRING']:
        return tree_stats, root, file_name
    else: 
        return tree_stats, root, None

def UpdateDF(df: pd.DataFrame, info: dict) -> pd.DataFrame: 
    '''Function to combine information stored in branch DataFrame obtained from running ParseTree and tree dictionary obtained from the ParseInitialTree or ParseTree function.'''

    df['ALI_ID'] = info['ALI_ID']
    df['IQTREE_VERSION'] = info['IQTREE_VERSION']
    df['RANDOM_SEED'] = info['RANDOM_SEED']
    df['TIME_STAMP'] = info['TIME_STAMP']
    df['TREE_TYPE'] = info['TREE_TYPE'] 

    return df

def ParseTreeBranches(tree_para, branch_para, iqtree_file, log_file, mldist_file, constant_stats, branch_number, tree_file, mode = 'out'): 
    '''
    Function that gathers all information to be stored in the tree and branch parameters files from the log, iqtree and tree files.
    It calles the function ParseTree from the parse_tree script.
    The results are written into tab seperated files (ali_parameters.tsv, model_parameters.tsv as well as seq_parameters.tsv).  
    ''' 
    global file_name_dic
    global name_dic
    global name_dic_unique

    # If there is a seperate intial tree file, parse all the informaton regarding said tree from its iqtree file.
    if initial_iqtree is not None: 
        tree_stats_in, root_in = ParseTreeParameters('initial', iqtree_file, mldist_file, branch_number, mode = mode)
        branch_stats_in, branches_df_in = ParseTree(prefix+'-initialtree.iqtree', name_dic = name_dic, rooted = root_in) 
    
    # If there is no seperate intial tree file, parse the initial tree and its parameters from the log file. 
    else: 
        if mode == 'out': 
            tree_stats_in, root_in, filename_in = ParseInitialTree(log_file, constant_stats, branch_number, name_dic_unique, mode = mode)
        if mode == 'keep': 
            tree_stats_in, root_in, filename_in = ParseInitialTree(log_file, constant_stats, branch_number, name_dic, mode = mode)
        if not not filename_in: 
            # Parse through the tree to get all BL and paths to external leaves.
            branch_stats_in, branches_df_in = ParseTree(filename_in, name_dic = name_dic, rooted = root_in) 

    # Repeat for the ML tree
    tree_stats_ml, root_ml = ParseTreeParameters('ml', iqtree_file, mldist_file, branch_number, mode = mode)
    branch_stats_ml, branches_df_ml = ParseTree(tree_file, name_dic = name_dic, rooted = root_ml) 

    # Drop split as it will not be included in database. 
    branches_df_in = branches_df_in.drop('SPLIT', axis=1)
    branches_df_ml = branches_df_ml.drop('SPLIT', axis=1)

    # Update the DataFrames with the information stored in the stats dictionaries
    tree_stats_in.update(branch_stats_in)
    tree_stats_ml.update(branch_stats_ml)
    branches_df_in = UpdateDF(branches_df_in, tree_stats_in)
    branches_df_ml = UpdateDF(branches_df_ml, tree_stats_ml)

    # Append results to the final DataFrames.
    tree_para = tree_para.append(tree_stats_in, ignore_index=True)
    tree_para = tree_para.append(tree_stats_ml, ignore_index=True)
    branch_para = branch_para.append(branches_df_in)
    branch_para = branch_para.append(branches_df_ml)

    # Write results into tab seperated files.
    tree_para.to_csv(file_name_dic['tree_para'], mode = 'a', sep  ='\t', index = False, header = False)
    print('...tree parameters were written into file '+file_name_dic['tree_para'])
    branch_para.to_csv(file_name_dic['branch_para'], mode = 'a', sep = '\t', index = False,header = False)
    print('...branch parameters were written into file '+file_name_dic['branch_para'])

def main(): 
    """
    Script to parse relevant information from IQ-Tree 2 output files to be imported into the EvoNAPS database.

    USAGE: 
    -------
    >>> parse_parameters.py [--prefix PREFIX_OF_INPUT_FILES] [--output PREFIX_OF_OUTPUT_FILES] [--ali NAME_OF_ALIGNMENT_FILE]

    OPTIONS: 
    -------
    --prefix or -p  
        Mandatory argument. Declares the path to and prefix of the IQ-Tree2 results files to be investigated. 
        Typically, the prefix will be the name of the alignment file that has been used as input for IQ-Tree 2.
    --output or -o 
        Option to declare the prefix of the output files. Default will be prefix from --prefix.
    --ali or -a
        Option to declare the name of the original alignment file, should it not coincide with the prefix.
    --help or -h
        Print information regarding script for help.

    OUTPUT: 
    -------
    The script writes the results into five different tab seperated files that are designed to be imported into the corresponding tables in the EvoNAPS database.

    EXAMPLE: 
    -------
    >>> parse_parametery.py --prefix example.fasta 
    example.fasta_ali_parameters.tsv
    example.fasta_seq_parameters.tsv
    example.fasta_model_parameters.tsv
    example.fasta_tree_parameters.tsv
    example.fasta_branch_parameters.tsv
    """
    global prefix
    global out_prefix
    global ali_file
    global tree_para
    global branch_para
    global constant_stats

    prefix = ''
    out_prefix = ''
    ali_file = ''

    for i in range (len(sys.argv)): 
        if sys.argv[i] == '--prefix' or sys.argv[i] == '-p': 
            prefix = sys.argv[i+1]
        if sys.argv[i] == '--output' or sys.argv[i] == '-o': 
            out_prefix = sys.argv[i+1]
        if sys.argv[i] == '--help' or sys.argv[i] == '-h': 
            print(main.__doc__)
            sys.exit(0)
        if sys.argv[i] == '--ali' or sys.argv[i] == '-a': 
            ali_file = sys.argv[i+1]

    print('***Script to parse relevant information from IQ-Tree 2 output files.')
    print('Output files are designed to be imported into the EvoNAPS database.***')

    if ali_file == '': 
        ali_file = prefix
    if out_prefix == '': 
        out_prefix = prefix     
    if prefix == '': 
        print('Error! Missing input. Type \''+str(sys.argv[0])+' --help\' for help.')
        sys.exit(2)

    # Check if files exits. Then open and read in files.
    print('\nChecking files...')
    CheckFiles()
    OpenFiles()

    # Check the alignment type (DNA or protein)
    print('\nChecking alignment type...')
    CheckAliType()
    if type == 'AA': 
        AA_models()
    print('...alignment type: '+type)

    # Initialise all dataframes.
    InitialiseDataFrames()
    numPara()

    # Start parsing through files...
    print('\nChecking files and filter all relevant information...')
    # Calculate state frequencies and create a dictionary with the names in the tree matched to the order in which they appear in in the alignment.
    CheckStateFreq()
    ParseAliSeqParameters() 
    constant_stats, branch_number = ParseModelParameters(iqtree, check, model_para, mode = 'out')
    ParseTreeBranches(tree_para, branch_para, iqtree, log, mldist, constant_stats, branch_number, prefix+'.treefile', mode = 'out')

    if unique is True and unique_ctrl is True: 
        constant_stats, branch_number = ParseModelParameters(iqtree_keep, check_keep, model_para, mode = 'keep')
        ParseTreeBranches(tree_para, branch_para, iqtree_keep, log_keep, mldist_keep, constant_stats, branch_number, prefix+'-keep_ident.treefile', mode = 'keep')

    print('\n***Exiting successfully***')

if __name__ == '__main__': 
    main()
