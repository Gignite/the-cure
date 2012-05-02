require 'nokogiri'

# Test path
TESTPATH = 'test'
COVERAGE = File.join(TESTPATH, 'coverage')
COVERAGE_XML = COVERAGE+'.xml'
JUNIT_XML = File.join('test', 'junit.xml')

# We don't want sh commands being verbose
RakeFileUtils.verbose_flag = false

desc 'Run unit tests, optionally quiet'
task :phpunit do |t, args|
  print 'Running unit tests...'
  sh 'phpunit > /dev/null', do |ok|
    puts ok ? ' they passed.' : ' some failed.'
  end
end

desc 'Inspect unit test and coverage reports'
task :inspect do
  print 'Reading junit XML...'
  junit = Nokogiri::XML(File.open(JUNIT_XML))
  suite = junit.at_css('testsuite')
  puts ' done.'
  
  print 'Reading coverage XML...'
  coverage = Nokogiri::XML(File.open(COVERAGE_XML))
  metrics = coverage.at_css('project > metrics')
  puts ' done.'

  puts ''
  
  puts " - #{metrics['ncloc']} non-commented lines of code"

  print " - #{metrics['classes']} classes with "
  print "#{metrics['methods']} methods ("
  print sprintf('%d', metrics['methods'].to_f / metrics['classes'].to_f)
  puts ' per class)'

  covered = sprintf('%d', metrics['coveredstatements'].to_f / metrics['statements'].to_f * 100)
  print " - #{metrics['statements']} statements with ", covered, '% covered ('
  print sprintf('%d', metrics['statements'].to_f / metrics['methods'].to_f)
  puts ' per method)'

  print " - #{suite['tests']} tests and "
  print "#{suite['assertions']} asserts "
  print 'with ' if suite['errors'] != '0' or suite['failures'] != '0'

  if suite['errors'] != '0'
    errors_pc = sprintf('%d', suite['errors'].to_f / suite['tests'].to_f * 100)
    print errors_pc, '% errors '
  end

  print ' and ' if suite['errors'] != '0' and suite['failures'] != '0'

  if suite['failures'] != '0'
    failures_pc = sprintf('%d', suite['failures'].to_f / suite['tests'].to_f * 100)
    print failures_pc, '% failures '
  end

  print '(in ', sprintf('%.2f', suite['time']), ' seconds)'

  puts '', ''
  print 'file://', File.realpath(File.join('test', 'coverage', 'index.html'))
  puts '', ''
end

desc 'Run tests and inspect'
task :test => [:phpunit, :inspect]

desc 'Clean up code coverage'
task :clean do
  [COVERAGE, COVERAGE_XML, JUNIT_XML].each do |f|
    print "Deleting #{f}..."
    rm_rf f
    puts ' done.'
  end
end

desc 'Shortcut for `rake test`'
task :default => [:test]